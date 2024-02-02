<?php


/**
 * Permissions config
 *
 * @author Muhammad Adnan <adnannadeem1994@gmail.com>
 * @date   23/10/2020
 */

return [
    /*'Statistics'=>
        [
            'Statistics' => 'joeys.statistics|joeys.basicRegistration|joeys.docSubmission|joeys.totalApplicationSubmissionTable|joeys.totalTrainingwatchedTable|joeys.totalQuizPassedTable',
        ],*/
    'Roles'=>
        [
            'Role List' => 'role.index',
            'Create' => 'role.create|role.store',
            'Edit' => 'role.edit|role.update',
            'View' => 'role.show',
            'Set Permissions' => 'role.set-permissions|role.set-permissions.update',
        ],
    'Sub Admins'=>
        [
            'Sub Admin List' => 'sub-admin.index|sub-admin.data',
            'Create' => 'sub-admin.create|sub-admin.store',
            'Edit' => 'sub-admin.edit|sub-admin.update',
            'Status Change' => 'sub-admin.active|sub-admin.inactive',
            'View' => 'sub-admin.show',
            'Delete' => 'sub-admin.destroy',
        ],
    'Joeys List'=>
        [
            'Joeys List' => 'joeys-list.index|joeys.data',
            'Edit' => 'joeys-list.edit|joeys-list.update',
            'Document Not Uploaded' => 'joeys.documentNotUploaded|joeys.documentNotUploadedData|joeys.documentNotUploadedNotification|joeys.bulkDocumentNotUploadedNotification',
            'Document Not Approved' => 'joeys.documentNotApproved|joeys.documentNotApprovedData',
            'Document Approved' => 'joeys.documentApproved|joeys.documentApprovedData',
            'Not Trained' => 'joeys.notTrained|joeys.notTrainedData|joeys.notTrainedNotification|joeys.bulkNotTrainedNotification',
            'Quiz Pending' => 'joeys.quizPending|joeys.quizPendingData|joeys.quizPendingNotification|joeys.bulkQuizPendingNotification',
            'Quiz Passed' => 'joeys.quizPassed|joeys.quizPassedData',
        ],
    'Joey Complaint List'=>
        [
            'Joey Complaint List' => 'joeys-complaints.index|joeys-complaints.data|joeys-complaints.statusUpdate',
        ],
    'Joey Document Verification'=>
        [
            'Joey Document Verification List' => 'joey-document-verification.index|joey-document-verification.data',
            'View' => 'joey-document-verification.show',
            'Edit' => 'joey-document-verification.edit|joey-document-verification.update',
            'Joey Expired Document List' => 'joey-document-verification.expiredDocument|joey-expired-document.data',
           /* 'Status change' => 'joey-document-verification.statusUpdate',*/
        ],
     'Joey Attempted Quiz'=>
        [
            'Joey Attempt Quiz List' => 'joey-attempt-quiz.index|joey-attempt-quiz.data',
            'View' => 'joey-attempt-quiz.show',

        ],
    'Joey Broadcasting Notification'=>
        [
            'Broadcasting Notification' => 'notification.index|notification.send',
        ],
    'Customer Send Messages'=>
        [
            'Customer Send Messages List' => 'customer-send-messages.index|customer-send-messages.data',
            'Create' => 'customer-send-messages.create|customer-send-messages.store',
            'Edit' => 'customer-send-messages.edit|customer-send-messages.update',
            'Delete' => 'customer-send-messages.destroy',
        ],
        'Customer Services'=>
        [
            'Flag List' => 'customer-service.index',
            'Create' => 'customer-service.create|customer-service.store',
            'Edit' => 'customer-service.edit|customer-service.update|customer-services.sub-category.delete',
            'Category Status Change' => 'customer-service.isEnable|customer-service.isDisable',
            'View' => 'customer-service.show',
        ],

     'Categories Order Count'=>
        [
            'Categories Order Count List' => 'categores.index|categores.data',
            'Create' => 'categores.create|categores.store',
            'Edit' => 'categores.edit|categores.update',
            'Delete' => 'categores.destroy',
        ],
    'Joey Checklists '=>
        [
            'Joey Checklists List' => 'joey-checklist.index|joey-checklist.data',
            'Create' => 'joey-checklist.create|joey-checklist.store',
            'Edit' => 'joey-checklist.edit|joey-checklist.update',
            'Delete' => 'joey-checklist.destroy',
        ],
    'Documents'=>
        [
            'Documents List' => 'documents.index|documents.data',
            'Create' => 'documents.create|documents.store',
            'Edit' => 'documents.edit|documents.update',
            'Delete' => 'documents.destroy',
        ],

    'Zones'=>
        [
            'Zones List' => 'zones.index|zones.data',
/*            'Create' => 'zones.create|zones.store',
            'Edit' => 'zones.edit|zones.update',
            'Delete' => 'zones.destroy',*/
        ],
    'Work Time'=>
        [
            'Prefered Work Time List' => 'work-time.index|work-time.data',
            'Create' => 'work-time.create|work-time.store',
            'Edit' => 'work-time.edit|work-time.update',
            'Delete' => 'work-time.destroy',
        ],
    'Work Type'=>
        [
            'Work Type List' => 'work-type.index|work-type.data',
            'Create' => 'work-type.create|work-type.store',
            'Edit' => 'work-type.edit|work-type.update',
            'Delete' => 'work-type.destroy',
        ],
    
/*    'Job Types '=>
        [
            'Job Types list' => 'job-type.index|job-type.data',
            'Create' => 'job-type.create|job-type.store',
            'Edit' => 'job-type.edit|job-type.update',
            'Delete' => 'job-type.destroy',
        ],*/

    
 /*   'Basic Vendors '=>
        [
            'Basic Vendors list' => 'basic-vendor.index|basic-vendor.data',
            'Create' => 'basic-vendor.create|basic-vendor.store',
            'Delete' => 'basic-vendor.destroy',
        ],
    'Basic Categories'=>
        [
            'Basic Categories list' => 'basic-category.index|basic-category.data',
            'Create' => 'basic-category.create|basic-category.store',
            'Delete' => 'basic-category.destroy',
        ],*/
/*    'Vendors Score'=>
        [
            'Vendors Score list' => 'vendor-score.index|vendor-score.data',
            'Create' => 'vendor-score.create|vendor-score.store',
            'Edit' => 'vendor-score.edit|vendor-score.update',
            'Delete' => 'vendor-score.destroy',
        ],
    'Categories Score'=>
        [
            'Categories Score list' => 'category-score.index|category-score.data',
            'Create' => 'category-score.create|category-score.store',
            'Edit' => 'category-score.edit|category-score.update',
            'Delete' => 'category-score.destroy',
        ],*/
    /*'Vendors'=>
        [
            'Vendors List' => 'vendors.index|vendors.data',
            'Create'=> 'vendors.create|vendors.store',
            'Edit' => 'vendors.edit|vendors.update',
            'Delete' => 'vendors.destroy',
        ],*/
    
/*    'Order Categories'=>
        [
            'Order Categories list' => 'order-category.index|order-category.data',
            'Create' => 'order-category.create|order-category.store',
            'Edit' => 'order-category.edit|order-category.update',
            'Delete' => 'order-category.destroy',
        ],*/
    'Setting'=>
        [
            'Setting Main Page' => 'dashboard.index',
            'Change Password' => 'users.change-password',
            'Edit Profile' => 'users.edit-profile',
        ],

    'Order Categories'=>
        [
            'Order Categories List' => 'order-category.index|order-category.data',
            'Create' => 'order-category.create|order-category.store',
            'Edit' => 'order-category.edit|order-category.update',
            'Delete' => 'order-category.destroy',
        ],

    'Training Videos and Documents'=>
        [
            'Training Videos & Documents List' => 'training.index|training.data',
            'Create' => 'training.create|training.store',
            'Edit' => 'training.edit|training.update',
            'Delete' => 'training.destroy',
        ],
   
    'Quizes Management'=>
        [
            'Quizes Management List' => 'quiz-management.index|quiz-management.data',
            'Create' => 'quiz-management.create|quiz-management.store',
            'Edit' => 'quiz-management.edit|quiz-management.update',
            'Delete' => 'quiz-management.destroy',
            'View' => 'quiz-management.show',
        ],

    'FAQs'=>
        [
            'FAQ List' => 'faqs.index|faqs.data',
            'Create' => 'faqs.create|faqs.store',
            'Edit' => 'faqs.edit|faqs.update',
            'Delete' => 'faqs.destroy',
        ],
   

];
