$(function () {
  var apiSource = "production"; /* local, staging, production */
  var isEnvTest = window.location.href.includes("testing."),
    baseUrl = isEnvTest
      ? "https://api2.testing.joeyco.com"
      : "https://api2.joeyco.com",
    SELECTOR = {
      messageArea: ".messageArea",
      chatHeader: ".chat_header",
      chatTextarea: ".chat_textarea",
      alertMessages: ".alert_messages",
      textMessage: ".textMessageInput",
      chatStackedIcon: ".chat_stacked_icon",
      chatWrap: ".chat_wrap",
      endThread: ".end_thread",
      sendMsgBtn: ".send_msg_btn",
      chatMessage: "#chat_message",
      endThreadBtn: "#endThreadBtn",
      // Joey page
      joeyChatIcon: ".joey_chat_icon",
      chat_user_id: '[name="chat_user_id"]',
      chat_other_user_id: '[name="chat_other_user_id"]',
      chat_thread_id: '[name="chat_thread_id"]',
      chat_user_type: '[name="chat_user_type"]',
      other_user_type: '[name="other_user_type"]',
      attachFileBtn: "#attachFileBtn",
      attachFileInput: "#attachFileInput",
      chatFiles: "#chatFiles",
      reasonCategoryDD: "#reasonCategoryDD",
      reasonDD: "#reasonDD",
      thread_list: "#thread_list",
      active_thread_list: "#active_thread_list",
      thread_box: ".thread_box",
      startChatBtn: ".startChatBtn",
      // Groups
      createGroupForm: "#create_group_form",
      group_list: ".group_list",
      group_box: ".group_box",
      add_member_form: ".add_member_form",
      isGroup: "#isGroup",
      chat_group_id: '[name="chat_group_id"]',
    },
    department = $('[name="department"]').val();

  let userId = $("#userId").val();
  let ip_address = "127.0.0.1:";
  let socket_port = "3000";
  let localURL = ip_address + socket_port;
  let url =
    apiSource === "production" ? "https://joeycochat.herokuapp.com" : localURL;
  let socket = io(url, {
    query: {
      userId: userId,
      user: $(SELECTOR.chat_user_type).val() + userId,
    },
  });
  // socket.on('connection');
  var SIN_TYPE = {
      TEMPORARY: "temporary",
      PERMANENT: "permanent",
    },
    COOKIES = {
      threadId: "threadId",
    };
  let API_ASSETS = baseUrl;
  let API_BASE_URL = `${baseUrl}/api/`;

  var SOCKETIO = {
    sendUpdateThreadToOnboarding: function () {
      socket.on("sendUpdateThreadToOnboarding", (params) => {
        console.log("received Thread socket", params);
        // if (params.department === department) {
        PAGE_CHAT.thread.loadPendingThread(params);
        // }
      });
    },
    init: function () {
      SOCKETIO.sendUpdateThreadToOnboarding();
    },
  };

  var GLOBAL = {
    isEmpty: function (el) {
      return !$.trim(el);
    },
    setCookie: function (name, value, days) {
      console.log("value: ", value);
      var expires = "";
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
    },
    getCookie: function (name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(";");
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == " ") c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    },
    removeCookie: function (name) {
      document.cookie =
        name + "=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
    },
  };
  let CHAT_DATA = "";
  let reasonCategories = [];
  let thread_reason_id = "";
  let GROUP_DATA = "";
  // --------------------- Common - [Start]
  var COMMON = {
    isGroup: function () {
      return $(SELECTOR.group_box + ".active").length ? true : false;
    },
    // On page load
    init: function () {},
  };
  // --------------------- Page Login - [Start]
  var MESSAGE_TYPE = {
      TEXT: "TEXT",
      FILE: "FILE",
      DOCUMENT: "DOCUMENT",
    },
    PAGE_CHAT = {
      REASONS: {
        validateReasonCategory: function () {},
        updateReasons: function () {
          var selectedReasonCategory = $(SELECTOR.reasonCategoryDD).val(),
            reasonBySelectedCategory = reasonCategories.find(
              (item) => item.id == selectedReasonCategory
            ),
            reasonsByCategory = reasonBySelectedCategory.thread_reason;

          $(SELECTOR.reasonDD).empty(reasonsByCategory);
          reasonsByCategory.map((reason) => {
            $(SELECTOR.reasonDD).append(`
                    <option value="${reason.id}" data-reason="${reason.id}">${reason.reason}</option>
                `);
          });
          PAGE_CHAT.REASONS.changeReasonDD();
        },
        changeReasonCategory: function () {
          $(SELECTOR.reasonCategoryDD).on("change", function () {
            PAGE_CHAT.REASONS.updateReasons();
          });
        },
        changeReasonDD: function () {
          $(SELECTOR.reasonDD).on("change", function () {
            thread_reason_id = $(SELECTOR.reasonDD).val();
          });
        },
        loadReasonCategory: function () {
          $.ajax({
            url: API_BASE_URL + "chat/thread/reason/list",
            type: "GET",
            success: function (result) {
              reasonCategories = result.body;

              $(SELECTOR.reasonCategoryDD).empty();
              reasonCategories.map((category, index) => {
                $(SELECTOR.reasonCategoryDD).append(`
                        <option value="${category.id}" data-categoryid="${category.id}">${category.reason}</option>
                    `);
              });
              PAGE_CHAT.REASONS.updateReasons();

              // $("#div1").html(result);
            },
            complete: function () {
              PAGE_CHAT.REASONS.changeReasonCategory();
            },
          });
        },
        init: function () {
          PAGE_CHAT.REASONS.loadReasonCategory();
        },
      },
      DROPDOWN: {
        closeAll: function () {
          $(".dd_wrap").removeClass("open");
        },
        show: function (thisDDBtn) {
          PAGE_CHAT.DROPDOWN.closeAll();
          thisDDBtn.closest(".dd_wrap").addClass("open");
        },
        hide: function (thisDDBtn) {
          thisDDBtn.closest(".dd_wrap").removeClass("open");
        },
        init: function () {
          $(".dd_btn").on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var thisDDBtn = $(this);
            if (thisDDBtn.closest(".dd_wrap").hasClass("open")) {
              PAGE_CHAT.DROPDOWN.hide(thisDDBtn);
            } else {
              PAGE_CHAT.DROPDOWN.show(thisDDBtn);
            }
          });
        },
        init2: function () {
          $(".dd_btn2").on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            var thisDDBtn = $(this);
            if (thisDDBtn.closest(".dd_wrap").hasClass("open")) {
              PAGE_CHAT.DROPDOWN.hide(thisDDBtn);
            } else {
              PAGE_CHAT.DROPDOWN.show(thisDDBtn);
            }
          });
        },
      },
      initData: function () {
        CHAT_DATA = {
          user_id: $('[name="chat_user_id"]').val(),
          other_user_id: $('[name="chat_other_user_id"]').val(),
          thread_id: $('[name="chat_thread_id"]').val(),
          user_type: $('[name="chat_user_type"]').val(),
          other_user_type: $('[name="other_user_type"]').val(),
          message: $('[name="chat_message"]').val(),
          message_type: MESSAGE_TYPE.TEXT,
          group_name: $('[name="groupName"]').val(),
          group_id: $('[name="chat_group_id"]').val(),
          source: $('[name="source"]').val(),
        };
      },
      checkThreadWritable: function (thread) {
        var isAccepted = thread.is_accepted,
          isThreadEnd = thread.is_thread_end;
        if (!isAccepted) {
          $(SELECTOR.chatHeader).hide();
          $(SELECTOR.chatTextarea).removeAttr("style");
          $(SELECTOR.alertMessages).empty();
          $(SELECTOR.endThread).hide();
          $(".no-result").removeClass("d-none").html(`
                    <div class="hgroup">
                        <div class="icon"><img src="/assets/front/assets/images/chat.png" /></div>
                        <h4>Pending Acceptance</h4>
                        <div class="divider center md"></div>
                        <p>Its seems your ticket is not accepted by our support, It usually happen when our support team are busy in assisting other Joeys like you. We usually response to the ticket in few seconds/minutes.</p>
                    </div>
                `);
        } else if (isThreadEnd) {
          $(SELECTOR.chatHeader).hide();
          $(SELECTOR.chatTextarea).removeAttr("style");
          $(SELECTOR.alertMessages).html(`
          <div>Ticket has been resolved</div>
          `);
          $(SELECTOR.endThread).hide();
        } else {
          $(SELECTOR.chatHeader).show();
          $(SELECTOR.alertMessages).empty();
          $(SELECTOR.chatTextarea).show();
          $(SELECTOR.endThread).show();
        }
      },
      message: {
        loadMessagesByThread: function (threadId) {
          var data = {
            user_id: CHAT_DATA.user_id,
            user_type: CHAT_DATA.user_type,
            other_user_id: CHAT_DATA.other_user_id,
            other_user_type: CHAT_DATA.other_user_type,
            thread_id: threadId,
            source: CHAT_DATA.source,
          };
          $.ajax({
            url: API_BASE_URL + "chat/message/chat",
            type: "POST",
            data: data,
            success: function (result) {
              let body = result.body;
              let messages = body.Messages;
              $(SELECTOR.messageArea).empty();
              PAGE_CHAT.checkThreadWritable(body);
              // PAGE_CHAT.thread.checkThread();
              if (messages.length) {
                const otherUserId = body.participants.id;
                // $(SELECTOR.chat_other_user_id).attr('value', otherUserId);
                PAGE_CHAT.initData();

                $(SELECTOR.thread_box + ".active")
                  .find(".unread_msg_count")
                  .html(0);
                PAGE_CHAT.checkUnseenMsgCount();

                messages.map((item, index) => {
                  const userId = parseInt(CHAT_DATA.user_id);
                  const senderId = parseInt(item.sender.id);
                  if (item.sender) {
                    const fullName = item.sender.full_name
                      ? item.sender.full_name
                      : `${item.sender.first_name} ${item.sender.last_name}`;
                    const msg = {
                      user: fullName,
                      message: item.message,
                      created_at: item.created_at,
                      files: item.files,
                      index: index,
                    };
                    PAGE_CHAT.appendMessage(
                      msg,
                      senderId === userId ? "outgoing" : "incoming"
                    );
                  }
                });
              }
              // $("#div1").html(result);
            },
          });
        },
      },
      thread: {
        initEndThread: function () {
          $(SELECTOR.endThreadBtn).on("click", function (e) {
            e.preventDefault();
            PAGE_CHAT.thread.endThread();
          });
        },
        endThread: function () {
          $.ajax({
            url: API_BASE_URL + "chat/end/threads",
            type: "POST",
            data: CHAT_DATA,
            success: function (result) {
              PAGE_CHAT.sendEndThreadToServer();
              GLOBAL.removeCookie(COOKIES.threadId);
              $(SELECTOR.messageArea).empty();
              PAGE_CHAT.thread.checkThread();
              // $("#div1").html(result);
              PAGE_CHAT.thread.listThread();
              PAGE_CHAT.thread.loadPendingThread();
            },
          });
        },
        checkIsThreadSelected: function (thread) {
          if ($(SELECTOR.thread_list + " .thread_box.active").length) {
            $(".no-result").addClass("d-none");
          } else {
            $(".no-result").removeClass("d-none");
          }
        },
        clickHandlerInit: function () {
          $(SELECTOR.thread_box + " .link").on("click", function (e) {
            e.preventDefault();
            var otherUserId = $(this).data("otheruserid"),
              threadId = $(this).data("threadid"),
              reasonCategory = $(this).data("reasoncategory"),
              otherUserType = $(this).data("otherusertype"),
              reason = $(this).data("reason");
            $(SELECTOR.chatHeader + " .reason_category").text(reasonCategory);
            $(SELECTOR.chatHeader + " .reason").text(reason);

            PAGE_CHAT.thread.updateThread(threadId);

            $(SELECTOR.thread_list + " .thread_box").removeClass("active");
            $(SELECTOR.group_list + " .group_box").removeClass("active");
            $(this).closest(".thread_box").addClass("active");

            PAGE_CHAT.thread.checkIsThreadSelected();
            $(SELECTOR.chat_other_user_id).attr("value", otherUserId);
            $(SELECTOR.other_user_type).attr("value", otherUserType);
            PAGE_CHAT.initData();
            PAGE_CHAT.message.loadMessagesByThread(threadId);
          });
        },
        checkThread: function () {
          var activeThread = $(SELECTOR.thread_box + ".active"),
            threadId = activeThread.data("threadid");
          if (GLOBAL.isEmpty(threadId)) {
            $(SELECTOR.endThread).slideUp();
          } else {
            $(SELECTOR.endThread).slideDown();
          }
        },
        appendThread: function (thread, isPending) {
          var reasonCategory = thread.thread_reason_parent;
          var reason = thread.thread_reason;
          var otheruserid = thread.other_user_id;
          var created_at = thread.last_message
            ? thread.last_message.created_at
            : "N/A";
          if (reasonCategory && reason) {
            var threadTemplate = `
                        <div class="thread_box link-wrap ${
                          !thread.is_thread_end && !thread.is_accepted
                            ? "not-accepted"
                            : ""
                        }" data-threadid="${thread.id}">
                            <a href="#" 
                                class="link" 
                                data-reasoncategory="${reasonCategory.reason}" 
                                data-reason="${reason.reason}" 
                                data-threadid="${thread.id}" 
                                data-otheruserid="${otheruserid}"
                                data-otherusertype="${thread.other_user_type}"
                                style="${
                                  !thread.is_thread_end && !thread.is_accepted
                                    ? "display: none"
                                    : ""
                                }"
                                ></a>
                            <h5 class="title regular">
                                <span class="bc1-light f11">${
                                  reasonCategory.reason
                                }: </span> 
                                <span class="reason">${reason.reason}</span>
                            </h5>
                            <ul class="meta_info no-list flexbox flex-center">
                                <li class="last_active"><span class="lbl">
                                    Last active:</span> ${created_at}
                                </li>
                                ${
                                  thread.is_thread_end
                                    ? '<li class="status bold">Completed</li>'
                                    : thread.is_accepted
                                    ? '<li class="status basecolor1 bold">Active</li>'
                                    : '<li class="status color-bright-red bold">Pending</li>'
                                }
                            </ul>
                            ${
                              !thread.is_thread_end && !thread.is_accepted
                                ? '<div class="acceptThreadBtn"><a href="#" class="btn btn-primary" data-threadId="' +
                                  thread.id +
                                  '" data-otheruserid="' +
                                  thread.other_user_id +
                                  '" data-otherusertype="' +
                                  thread.other_user_type +
                                  '">Accept Thread</a></div>'
                                : '<div class="unread_msg_count">0</div>'
                            }
                        </div>
                    `;
            if (!thread.is_thread_end && !thread.is_accepted) {
              $(SELECTOR.thread_list).prepend(threadTemplate);
            } else {
              $(SELECTOR.thread_list).append(threadTemplate);
            }
          }
        },
        listThread: function () {
          var data = {
            user_id: CHAT_DATA.user_id,
            user_type: CHAT_DATA.user_type,
            source: CHAT_DATA.source,
          };
          $.ajax({
            url: API_BASE_URL + "chat/user/all/threads",
            type: "POST",
            data: data,
            success: function (result) {
              var threadsList = result.body;
              if (result.body.length) {
                $(
                  SELECTOR.thread_list + " .thread_box:not(.not-accepted)"
                ).remove();
                threadsList
                  // .filter(
                  //   (item) =>
                  //     item.thread_reason_parent.department === department
                  // )
                  .map(function (thread, index) {
                    PAGE_CHAT.thread.appendThread(thread);
                  });
              } else {
                $(SELECTOR.active_thread_list).html(`
                  <div class="no-thread-found">
                      <h6>No active thread found</h6>
                  </div>
                `);
              }
            },
            complete: function () {
              PAGE_CHAT.REASONS.changeReasonCategory();
              PAGE_CHAT.thread.clickHandlerInit();
              PAGE_CHAT.thread.initEndThread();
              PAGE_CHAT.checkUnseenMsgCount();
            },
          });
        },
        loadPendingThread: function () {
          var data = {
            user_id: CHAT_DATA.user_id,
            user_type: CHAT_DATA.user_type,
            source: CHAT_DATA.source,
          };
          $.ajax({
            url: API_BASE_URL + "chat/thread/list",
            type: "POST",
            data: data,
            success: function (result) {
              let body = result.body;
              const threads = body.filter((i) => i.thread_reason);
              console.log("threads::::", threads);
              if (threads && threads.length) {
                threads
                  // .filter(
                  //   (item) =>
                  //     item.thread_reason_parent.department === department
                  // )
                  .map((thread) => {
                    PAGE_CHAT.thread.appendThread(thread, true);
                  });
                PAGE_THREAD.inintAcceptThread();
              } else {
                if (!$(SELECTOR.thread_list + " .no-thread-found").length) {
                  // No pending thread found
                  $(SELECTOR.thread_list + " .row").prepend(`
                      <div class="no-thread-found col-sm-12">
                          <h6>No pending thread found</h6>
                      </div>
                  `);
                }
              }

              // $("#div1").html(result);
            },
          });
        },
        updateThread: function (threadId) {
          $(SELECTOR.chat_thread_id).attr("value", threadId);
          PAGE_CHAT.initData();
        },
        createThread: function (msg, name) {
          var data = {
            user_id: CHAT_DATA.user_id,
            user_type: CHAT_DATA.user_type,
            thread_reason_id: thread_reason_id,
            source: CHAT_DATA.source,
          };
          $.ajax({
            url: API_BASE_URL + "chat/create/threads",
            type: "POST",
            data: data,
            success: function (result) {
              const threadId = result.body.thread_id;
              PAGE_CHAT.thread.updateThread(threadId);
              // threadInput.val(threadId);
              PAGE_CHAT.initData();
              PAGE_CHAT.thread.updateThreadToOnboarding();
              // Send first message with thread
              // PAGE_CHAT.sendMessage(msg, name);
              // PAGE_CHAT.saveMessage();
              PAGE_CHAT.thread.listThread();
              PAGE_CHAT.thread.checkThread();

              $(".btn-loading").removeClass("btn-loading");
              $(".form_create_ticket_wrap").removeClass("open");
              // $("#div1").html(result);
            },
          });
        },
        updateThreadToOnboarding: function () {
          console.log("Thread should update to onbording");
          socket.emit("updateThreadToOnboarding");
        },
      },
      saveMessage: function (msg) {
        let formData = new FormData();
        formData.append("user_id", CHAT_DATA.user_id);
        formData.append("user_type", CHAT_DATA.user_type);
        formData.append("message", CHAT_DATA.message);
        formData.append("message_type", CHAT_DATA.message_type);
        formData.append("thread_id", CHAT_DATA.thread_id);
        formData.append("source", CHAT_DATA.source);

        var files = $(SELECTOR.attachFileInput)[0].files;
        if (files.length) {
          console.log(`${files.length} File found`);
          for (var i = 0, file; (file = files[i]); i++) {
            formData.append("files[" + [i] + "]", file);
          }
        }

        $.ajax({
          url: API_BASE_URL + "chat/send/message",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function (result) {
            let body = result.body;
            console.log("body::", body);
            let msgFiles = body.files;
            $(SELECTOR.attachFileInput).val("");
            $(SELECTOR.textMessage).val("");
            if (msgFiles && msgFiles.length) {
              msg.files = msgFiles;
            }
            var sender = result.body.sender;
            if (sender) {
              userFullName = sender.full_name
                ? sender.full_name
                : `${sender.first_name}  ${sender.last_name}`;
            }
            msg.user = userFullName;
            PAGE_CHAT.appendMessage(msg, "outgoing");
            $(SELECTOR.chatFiles).empty();
            PAGE_CHAT.sendMessageToServer(msg);

            // PAGE_CHAT.appendMessage(msg, 'outgoing');
            // msg.threadId = GLOBAL.getCookie(COOKIES.threadId);
            // PAGE_CHAT.sendMessageToServer(msg);
          },
        });
      },
      scrollToBottom: function () {
        var messageArea = $(SELECTOR.messageArea);
        messageArea.stop().animate(
          {
            scrollTop: messageArea[0].scrollHeight,
          },
          500
        );
      },
      closeChatBox: function () {
        $(SELECTOR.chatWrap).removeClass("opened");
        $(SELECTOR.chatStackedIcon)
          .find(".icofont-close")
          .removeClass("icofont-close")
          .addClass("icofont-chat");
      },
      openChatBox: function () {
        $(SELECTOR.chatWrap).addClass("opened");
        $(SELECTOR.chatStackedIcon)
          .find(".icofont-chat")
          .removeClass("icofont-chat")
          .addClass("icofont-close");
      },
      appendMessage: function (msg, type) {
        console.log("should append: ", msg);
        $(SELECTOR.messageArea).append(`
                <div class="chat_box ${type}" data-index="${msg.index}">
                    <div class="inner">
                        <h4 class="name">
                            ${msg.user}
                        </h4>
                        <div class="message">
                            <p>${msg.message}</p>
                            ${
                              msg.files && msg.files.length
                                ? '<div class="attachments"></div>'
                                : ""
                            }
                        </div>
                    </div>
                    <div class="created_at">${msg.created_at}</div>
                </div>`);

        var files = msg.files;
        if (files && files.length) {
          files.map(function (file, index) {
            $(`[data-index="${msg.index}"] .attachments`).append(`
                        <div class="file">
                            <a href="${
                              API_ASSETS + file.file_name
                            }" target="_blank">${file.file_name.replace("public/", "")}</a>
                            <i class="icofont-eye-alt"></i>
                        </div>
                    `);
          });
        }

        $(SELECTOR.textMessage).val("");
        PAGE_CHAT.scrollToBottom();
      },
      sendMessageToServer: function (msg) {
        console.log("sending message to server:", msg);
        console.log("sendMessageToServer msg:", msg);
        socket.emit("sendMessage", msg);
      },
      threadUnseenMessageCount: function (threadId) {
        var activeThread = $(
          SELECTOR.thread_box + '[data-threadid="' + threadId + '"]'
        );
        var currentCount = activeThread.find(".unread_msg_count").text();
        var increasedCount = parseInt(currentCount) + 1;
        activeThread.find(".unread_msg_count").html(increasedCount);
        activeThread.find(".unread_msg_count").addClass("animate");
        setTimeout(function () {
          activeThread.find(".unread_msg_count").removeClass("animate");
        }, 3000);
      },
      checkUnseenMsgCount: function () {
        $(SELECTOR.thread_box).each(function () {
          var msgCount = parseInt($(this).find(".unread_msg_count").html());
          if (!msgCount) {
            $(this).find(".unread_msg_count").hide();
          } else {
            $(this).find(".unread_msg_count").show();
          }
        });
      },
      handleMessageReceiveThread: function (params) {
        var threadId = params.threadId;
        var otherUserId = params.otherUserId;
        $(SELECTOR.chat_other_user_id).attr("value", otherUserId);
        $(SELECTOR.chat_thread_id).attr("value", threadId);

        PAGE_CHAT.initData();

        var activeThread = $(SELECTOR.thread_box + ".active");
        if (activeThread.length && activeThread.data("threadid") == threadId) {
          PAGE_CHAT.appendMessage(params, "incoming");
          PAGE_CHAT.thread.updateThread(params.threadId);
          PAGE_CHAT.thread.checkThread();
        } else {
          console.log("Same thread is not selected as incoming message");
        }
        PAGE_CHAT.threadUnseenMessageCount(threadId);

        $(SELECTOR.thread_box + '[data-threadid="' + threadId + '"].active')
          .find(".unread_msg_count")
          .html(0);
        PAGE_CHAT.checkUnseenMsgCount();
      },
      handleMessageReceiveGroup: function (params) {
        var groupId = params.group_id;
        $(SELECTOR.chat_group_id).attr("value", groupId);
        PAGE_CHAT.initData();

        var activeGroup = $(SELECTOR.group_box + ".active");
        if (activeGroup.length && activeGroup.data("groupid") == groupId) {
          PAGE_CHAT.appendMessage(params, "incoming");
        } else {
          console.log("Same group is not selected as incoming message");
        }
        SECTION_GROUPS.group.unseenMessageCount(groupId);

        $(SELECTOR.thread_box + '[data-threadid="' + threadId + '"].active')
          .find(".unread_msg_count")
          .html(0);
        PAGE_CHAT.checkUnseenMsgCount();
      },
      receiveMessageFromServer: function () {
        socket.on("sendReceivedMessage", (params) => {
          console.log("received message from server", params);
          if (params.is_group) {
            PAGE_CHAT.handleMessageReceiveGroup(params);
          } else {
            PAGE_CHAT.handleMessageReceiveThread(params);
          }
        });
      },
      sendEndThreadToServer: function () {
        let params = {
          threadId: CHAT_DATA.thread_id,
          userId: CHAT_DATA.other_user_id,
          to: $(SELECTOR.other_user_type).val() + CHAT_DATA.other_user_id,
        };
        PAGE_CHAT.thread.updateThreadToOnboarding();
        socket.emit("endThread", params);
      },
      sendAcceptThreadToServer: function (thread_id, thread) {
        let params = {
          threadId: CHAT_DATA.thread_id,
          userId: CHAT_DATA.other_user_id,
          otherUserId: CHAT_DATA.user_id,
          otherUserType: CHAT_DATA.user_type,
          thread: thread,
          to: $(SELECTOR.other_user_type).val() + CHAT_DATA.other_user_id,
        };
        socket.emit("acceptThread", params);
      },
      endThreadFromServer: function () {
        GLOBAL.removeCookie(COOKIES.threadId);
        $(SELECTOR.messageArea).empty();
        PAGE_CHAT.thread.checkThread();
        // $("#div1").html(result);
        PAGE_CHAT.thread.listThread();
        PAGE_CHAT.thread.loadPendingThread();
        // alert("Thread ended successfully");
      },
      receiveEndThreadStatus: function () {
        socket.on("sendThreadStatus   ", (params) => {
          console.log("001 sendThreadStatus");
          PAGE_CHAT.endThreadFromServer(params);
        });
      },
      acceptThreadFromServer: function (params) {
        console.log("params2: ", params);
        var selectThreadById = $(
          SELECTOR.thread_box + '[data-threadid="' + params.threadId + '"]'
        );
        selectThreadById
          .find(".meta_info .status")
          .removeClass("color-bright-red")
          .addClass("basecolor1")
          .html("Active");
        selectThreadById.find(".link").trigger("click");
        // selectThreadById.find('.link').trigger('click');
      },
      sendMessage: function (msg, name) {
        var textMessageInput = $(SELECTOR.textMessage).val();
        $(SELECTOR.chatMessage).attr("value", textMessageInput);
        PAGE_CHAT.initData();
        if (!GLOBAL.isEmpty(CHAT_DATA.message)) {
          let message = CHAT_DATA.message;
          let date = new Date();
          let formatedDate =
            date.getFullYear() +
            "/" +
            (date.getMonth() + 1) +
            "/" +
            date.getDate() +
            " - " +
            date.getHours() +
            ":" +
            date.getMinutes() +
            ":" +
            date.getSeconds();
          // let formatedDate = date.getDate()  + "-" + (date.getMonth()+1) + "-" + date.getFullYear() + " " + date.getHours() + ":" + date.getMinutes();
          var files = $(SELECTOR.attachFileInput)[0].files;
          let msg = {
            user: name,
            userId: userId,
            message: message.trim(),
            created_at: formatedDate,
          };

          // Check if group
          if (COMMON.isGroup()) {
            console.log("Its group");
            var activeGroup = $(SELECTOR.group_box + ".active");
            var groupId = activeGroup.data("groupid");
            msg.group_id = groupId;
            msg.isGroup = true;
            msg.to = "room:" + groupId;
            SECTION_GROUPS.MESSAGE.save(msg);
          } else {
            console.log("Its chat");
            var activeThread = $(SELECTOR.thread_box + ".active");
            var threadId = activeThread.data("threadid");
            msg.threadId = threadId;
            msg.isGroup = false;
            msg.to =
              $(SELECTOR.other_user_type).val() + CHAT_DATA.other_user_id;
            PAGE_CHAT.saveMessage(msg);
          }
        }
      },
      init: function () {
        // On chat icon click
        $(SELECTOR.chatStackedIcon).on("click", function () {
          if ($(this).find("i").hasClass("icofont-chat")) {
            PAGE_CHAT.openChatBox();
          } else {
            PAGE_CHAT.closeChatBox();
          }
        });

        let name = $("#name").text();
        let textarea = $(".textMessageInput");

        $('#ticket_reasons_form [type="submit"]').on("click", function (e) {
          e.preventDefault();
          $(this).addClass("btn-loading");
          PAGE_CHAT.thread.createThread();
        });

        textarea.on("keyup", (e) => {
          if (e.key === "Enter") {
            console.log("Should save message");
            PAGE_CHAT.sendMessage();
          }
        });

        $(SELECTOR.sendMsgBtn).on("click", function (e) {
          e.preventDefault();
          PAGE_CHAT.sendMessage();
        });

        PAGE_CHAT.receiveMessageFromServer();
        PAGE_CHAT.receiveEndThreadStatus();

        PAGE_CHAT.initData();
        var activeThread = $(SELECTOR.thread_box + ".active"),
          threadId = activeThread.data("threadid");
        if (!GLOBAL.isEmpty(threadId)) {
          // PAGE_CHAT.message.loadMessagesByThread();
        }
        PAGE_CHAT.thread.listThread();
        PAGE_CHAT.thread.loadPendingThread();
        PAGE_CHAT.thread.checkThread();
        PAGE_CHAT.attachment.init();

        PAGE_CHAT.DROPDOWN.init();
        PAGE_CHAT.REASONS.init();
      },

      // Files attachment
      attachment: {
        attachFileInputChangeInit: function () {
          $(SELECTOR.attachFileInput).on("change", function () {
            console.log(13123);
            PAGE_CHAT.attachment.attachFileInputChange();
          });
        },
        attachFileInputChange: function () {
          var files = $(SELECTOR.attachFileInput)[0].files;
          for (var i = 0, file; (file = files[i]); i++) {
            var image = URL.createObjectURL(file);
            $(SELECTOR.chatFiles).append(`
                        <div class="file">
                            <img src="${image}"/>
                        </div>
                    `);
          }
        },
        clickAttachIcon: function () {
          $(SELECTOR.attachFileBtn).on("click", function () {
            $(SELECTOR.attachFileInput).trigger("click");
          });
        },
        init: function () {
          PAGE_CHAT.attachment.clickAttachIcon();
          PAGE_CHAT.attachment.attachFileInputChangeInit();
        },
      },
    },
    // Group chat
    SECTION_GROUPS = {
      MESSAGE: {
        loadMessagesByGroup: function (groupId) {
          console.log(groupId);
          var data = {
            group_id: groupId,
            source: CHAT_DATA.source,
          };
          console.log("group_data: ", data);
          $.ajax({
            url: API_BASE_URL + "chat/group/chat/message",
            type: "POST",
            data: data,
            success: function (result) {
              let body = result.body;
              let messages = body.messages;
              $(SELECTOR.messageArea).empty();
              // PAGE_CHAT.thread.checkThread();
              $(SELECTOR.alertMessages).empty();
              $(SELECTOR.chatTextarea).show();

              if (messages.length) {
                $(SELECTOR.thread_box + ".active")
                  .find(".unread_msg_count")
                  .html(0);
                PAGE_CHAT.checkUnseenMsgCount();

                messages.map((item, index) => {
                  const userId = parseInt(CHAT_DATA.user_id);
                  const senderId = parseInt(item.sender.id);
                  const fullName =
                    item.sender.first_name + " " + item.sender.last_name;
                  const msg = {
                    user: fullName,
                    message: item.message,
                    created_at: item.created_at,
                    files: item.files,
                    index: index,
                  };
                  PAGE_CHAT.appendMessage(
                    msg,
                    senderId === userId ? "outgoing" : "incoming"
                  );
                });
              }
            },
          });
        },
        checkIsGroupSelected: function (group) {
          if ($(SELECTOR.group_list + " .group_box.active").length) {
            $(".no-result").addClass("d-none");
          } else {
            $(".no-result").removeClass("d-none");
          }
        },
        save: function (msg) {
          let formData = new FormData();
          formData.append("user_id", CHAT_DATA.user_id);
          formData.append("user_type", CHAT_DATA.user_type);
          formData.append("message", CHAT_DATA.message);
          formData.append("message_type", CHAT_DATA.message_type);
          formData.append("group_id", CHAT_DATA.group_id);
          formData.append("source", CHAT_DATA.source);

          var files = $(SELECTOR.attachFileInput)[0].files;
          if (files.length) {
            console.log(`${files.length} File found`);
            for (var i = 0, file; (file = files[i]); i++) {
              formData.append("files[" + [i] + "]", file);
            }
          }

          $.ajax({
            url: API_BASE_URL + "chat/group/send/message",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
              console.log("group result resp: ", result);
              let body = result.body;
              let msgFiles = body.files;
              $(SELECTOR.attachFileInput).val("");
              $(SELECTOR.textMessage).val("");
              if (msgFiles && msgFiles.length) {
                msg.files = msgFiles;
              }
              var userFullName = "";
              if (result.body.sender) {
                userFullName =
                  result.body.sender.first_name +
                  " " +
                  result.body.sender.last_name;
              }
              msg.user = userFullName;
              PAGE_CHAT.appendMessage(msg, "outgoing");
              $(SELECTOR.chatFiles).empty();
              PAGE_CHAT.sendMessageToServer(msg);
            },
          });
        },
        updateGroupInput: function (groupId) {
          $(SELECTOR.chat_group_id).attr("value", groupId);
          PAGE_CHAT.initData();
        },
        init: function () {
          $(".group_link").on("click", function (e) {
            e.preventDefault();
            var groupId = $(this).data("groupid");
            $(this).addClass("active");

            $(SELECTOR.thread_list + " .thread_box").removeClass("active");
            $(SELECTOR.group_list + " .group_box").removeClass("active");
            $(this).closest(".group_box").addClass("active");
            SECTION_GROUPS.MESSAGE.checkIsGroupSelected(groupId);
            SECTION_GROUPS.MESSAGE.updateGroupInput(groupId);

            // console.log('otherUserId: ', otherUserId);
            // $(SELECTOR.chat_other_user_id).attr('value', otherUserId);
            // $(SELECTOR.other_user_type).attr('value', otherUserType);
            // PAGE_CHAT.initData();
            // PAGE_CHAT.message.loadMessagesByThread(threadId);
            SECTION_GROUPS.MESSAGE.loadMessagesByGroup(groupId);
          });
        },
      },
      socket: {
        inviteUser: function (group, participant) {
          let memberNGroupInfo = {
            group_name: group.name,
            group_id: group.id,
            user_id: participant.user_id,
            participant_id: participant.id,
            user_type: participant.creator_type,
            to: participant.creator_type + participant.user_id,
          };
          socket.emit("inviteUser", memberNGroupInfo);
        },
        joinRoom: function (user, groupId) {
          let roomUserData = {
            room: "room:" + groupId,
            userNType: user,
          };
          console.log("roomUserData: ", roomUserData);
          socket.emit("joinRoom", roomUserData);
        },
      },
      group: {
        unseenMessageCount: function (groupId) {
          var activeGroup = $(
            SELECTOR.thread_box + '[data-groupid="' + groupId + '"]'
          );
          var currentCount = activeGroup.find(".unread_msg_count").text();
          var increasedCount = parseInt(currentCount) + 1;
          activeGroup.find(".unread_msg_count").html(increasedCount);
          activeGroup.find(".unread_msg_count").addClass("animate");
          setTimeout(function () {
            activeGroup.find(".unread_msg_count").removeClass("animate");
          }, 3000);
        },
        create: function () {
          var groupName = $('[name="groupName"]').val();
          var data = {
            user_id: CHAT_DATA.user_id,
            user_type: CHAT_DATA.user_type,
            group_name: groupName,
            source: CHAT_DATA.source,
          };
          $.ajax({
            url: API_BASE_URL + "chat/message-groups/create",
            type: "POST",
            data: data,
            success: function (result) {
              var body = result.body;
              const user = CHAT_DATA.user_type + CHAT_DATA.user_id;
              const groupId = body.id;
              // $(SELECTOR.other_user_type).val() + CHAT_DATA.other_user_id

              SECTION_GROUPS.socket.joinRoom(user, groupId);
            },
            complete: function () {
              SECTION_GROUPS.group.load();
            },
          });
        },
        load: function () {
          var data = {
            user_id: CHAT_DATA.user_id,
            user_type: CHAT_DATA.user_type,
            source: CHAT_DATA.source,
          };
          $.ajax({
            url: API_BASE_URL + "chat/user/allgroup/detail",
            type: "POST",
            data: data,
            success: function (result) {
              const groups = result.body;
              $(SELECTOR.group_list).empty();
              if (groups.length) {
                groups.map(function (group, index) {
                  SECTION_GROUPS.group.append(group);
                });
              }
              SECTION_GROUPS.MEMBERS.init();
              SECTION_GROUPS.MESSAGE.init();
              PAGE_CHAT.DROPDOWN.init2();
            },
          });
        },
        append: function (group) {
          var groupMember =
            group.group_member && group.group_member.length
              ? group.group_member
              : [];
          var groupTemplate = `
                <div class="group_box link-wrap" data-groupid="${group.id}">
                    <a href="#" class="link group_link" data-groupid="${
                      group.id
                    }"></a>
                    <div class="unread_msg_count">0</div>
                    <div class="group_title_wrap flexbox flex-center justify-content-between">
                        <h5 class="title regular">${group.group_name}</h5>
                        <div class="form_add_member_wrap dd_wrap position-r">
                            <button class="dd_btn2 btn btn-basecolor1 btn-border btn-icon btn-mb">
                                <i class="fa fa-plus"></i>
                            </button>
                            <div class="form_add_member_box dd_box">
                                <form method="GET" action="" class="add_member_form needs-validation" novalidate="">                                
                                    <input type="hidden" name="groupId" value="${
                                      group.id
                                    }" />
                                    <div class="form-group no-min-h">
                                        <label for="email">Email address</label>
                                        <input class="form-control form-control-lg" type="email" name="email" required>
                                    </div>
                                    <div class="form-group no-min-h">
                                        <label for="email">User Type</label>
                                        <select class="form-control" name="userType">
                                            <option value="Joey">Joey</option>
                                            <option value="dashboard">Dashboard</option>
                                            <option value="onboarding">Onboarding</option>
                                        </select>
                                    </div>
                                    <div class="btn-group nomargin">
                                        <button type="submit" class="btn btn-primary submitButton">Send Invite</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <ul class="group_users flexbox flex-center">
                        <li class="users_count_wrap"><div class="users_count flexbox flex-center jc-center">${
                          group.group_member_count
                            ? group.group_member_count + "+"
                            : 0
                        }</div></li>
                        ${groupMember
                          .map((member) => {
                            var avatar = member.image
                              ? member.image
                              : "https://www.joeyco.com/images/logo-joeyco.webp";
                            var fullName =
                              member.first_name + " " + member.last_name;
                            return `<li><div class="thumb">
                                    <img src="${avatar}" alt="${fullName}"/>
                                </div></li>`;
                          })
                          .join("")}
                    </ul>
                </div>
                `;
          $(SELECTOR.group_list).append(groupTemplate);
        },
      },
      initCreateGroupSubmit: function () {
        $(SELECTOR.createGroupForm).on("submit", function (e) {
          e.preventDefault();
          SECTION_GROUPS.group.create();
        });
      },
      MEMBERS: {
        list: function () {},
        show: function () {},
        hide: function () {},
        submit: function (thisForm) {
          var email = thisForm.find('[name="email"]').val();
          var userType = thisForm.find('[name="userType"]').val();
          var groupId = thisForm.find('[name="groupId"]').val();
          var data = {
            email: email,
            user_type: userType,
            group_id: groupId,
            source: CHAT_DATA.source,
          };
          console.log("data: ", data);
          $.ajax({
            url: API_BASE_URL + "chat/add/group/member",
            type: "POST",
            data: data,
            success: function (result) {
              console.log("member success: ", result);
              var body = result.body;
              var group = body.group;
              var participant = body.participant;

              SECTION_GROUPS.socket.inviteUser(group, participant);
            },
            complete: function () {
              PAGE_CHAT.DROPDOWN.closeAll();
              SECTION_GROUPS.group.load();
            },
          });
        },
        init: function () {
          $(".addMemberBtn").on("click", function () {});

          $(SELECTOR.add_member_form).on("submit", function (e) {
            e.preventDefault();
            var thisform = $(this);
            SECTION_GROUPS.MEMBERS.submit(thisform);
          });
        },
      },
      init: function () {
        SECTION_GROUPS.initCreateGroupSubmit();
        SECTION_GROUPS.group.load();
      },
    },
    // --------------------- Page Login - [/end]

    /* Initializing on joey loading from ajax inside custom.js
     * Page Joey - [Start]
     */
    PAGE_JOEY_LIST = {
      loadActiveMessages: function () {
        PAGE_CHAT.thread.listThread();
        PAGE_CHAT.thread.loadPendingThread();
      },
      loadChatByJoy: function (userId) {
        console.log("should load messages");
        $(SELECTOR.chat_other_user_id).attr("value", userId);
        PAGE_CHAT.initData();
        CHAT_DATA.byUser = true;
        PAGE_JOEY_LIST.loadActiveMessages();
        PAGE_CHAT.openChatBox();
        PAGE_CHAT.thread.checkThread();
      },
      init: function () {
        $(SELECTOR.joeyChatIcon).on("click", function (e) {
          e.preventDefault();
          let userId = $(this).data("id");
          PAGE_JOEY_LIST.loadChatByJoy(userId);
        });
      },
    },
    /* Initializing on joey loading from ajax inside custom.js
     * Page Joey - [/end]
     */

    PAGE_THREAD = {
      initStartChat: function () {
        $(SELECTOR.startChatBtn + " .btn").on("click", function (e) {
          e.preventDefault();
          $(SELECTOR.chat_thread_id).attr("value", $(this).data("threadid"));
          PAGE_CHAT.initData();
          let userId = $(this).data("userid");
          PAGE_JOEY_LIST.loadChatByJoy(userId);
        });
      },
      inintAcceptThread: function () {
        $(".acceptThreadBtn a").on("click", function (e) {
          e.preventDefault();
          console.log("otheruserid: ", $(this).data("otheruserid"));
          $(SELECTOR.chat_thread_id).attr("value", $(this).data("threadid"));
          $(SELECTOR.chat_other_user_id).attr(
            "value",
            $(this).data("otheruserid")
          );
          $(SELECTOR.other_user_type).attr(
            "value",
            $(this).data("otherusertype")
          );
          PAGE_CHAT.initData();
          PAGE_THREAD.acceptThread();
        });
      },
      acceptThread: function () {
        var data = {
          user_id: CHAT_DATA.user_id,
          user_type: CHAT_DATA.user_type,
          thread_id: CHAT_DATA.thread_id,
          source: CHAT_DATA.source,
        };
        $.ajax({
          url: API_BASE_URL + "chat/add/participants",
          type: "POST",
          data: data,
          success: function (result) {
            console.log(result);
            var body = result.body;
            let threadId = body.thread_id;
            if (result.status) {
              console.log("user_id: ", CHAT_DATA.user_id);
              $(
                SELECTOR.thread_box +
                  ` a[data-threadid="${CHAT_DATA.thread_id}"]`
              )
                .closest(".thread_box")
                .remove();

              PAGE_JOEY_LIST.loadActiveMessages();
              PAGE_CHAT.openChatBox();

              PAGE_CHAT.thread.listThread();
              PAGE_CHAT.thread.checkThread();
              PAGE_CHAT.sendAcceptThreadToServer(threadId, body);
            } else {
              alert(
                "This thread has already accepted by other on boarding user"
              );
            }
          },
        });
      },
      init: function () {
        PAGE_CHAT.thread.listThread();
      },
    };

  COMMON.init();
  SOCKETIO.init();
  PAGE_CHAT.init();
  SECTION_GROUPS.init();

  PAGE_THREAD.init();
  // PAGE_JOEY_LIST.init();
});
