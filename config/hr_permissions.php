<?php


/**
 * Permissions config
 *
 * @author Muhammad Adnan <adnannadeem1994@gmail.com>
 * @date   23/10/2020
 */

return [
    'User Profile'=>
        [

            'Edit' => 'users.edit-profile',
            'Change Password' => 'users.change-password',
        ],
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
    'Users'=>
        [
            'User List' => 'users.index|attendance.users.data',
            'Edit' => 'users.edit|users.update',
            'Status Change' => 'users.active|users.block',
        ],
    'New Requests'=>
        [
            'New Request List' => 'new-request.index|new-request.data',
            'Edit' => 'unverified-users.edit|unverified-users.update',
            'Delete' => 'new-request.delete',
            'Status Change' => 'new-request.verified|new-request.unverified|ajaxRequestForChecking-WorkingDays.post',
        ],

    'Attendances'=>
        [
            'Attendance List' => 'attendance.index|attendance.data',
            'Create' => 'attendance.addAttendance|attendance.createAttendance',
            'Edit' => 'attendance.edit|attendance.update',
        ],
    'Attendances Report '=>
        [
            'Attendance Report' => 'attendance.report|attendanceReportExcel.data',
            'Attendance Excel' => 'export_reporting.excel',
        ],
    'Pages'=>
        [
            'Edit' => 'attendancePage.edit|attendancePage.update',

        ],
    'Departments'=>
        [
            'Department List' => 'department.index|department.data',
            'Create' => 'department.create|department.store',
            'Edit' => 'department.edit|department.update',
            'Status Change' => 'department.active|department.inactive',
        ],
    'Public Holidays'=>
        [
            'Public Holidays List' => 'public_holidays.index|public_holidays.data',
            'Create' => 'public_holidays.create|public_holidays.insert',
            'Edit' => 'public_holidays.edit|public_holidays.update',
            'Delete' => 'public_holidays.delete',
        ],
    'Hourly Report'=>
        [
            'Hourly Report' => 'attendance.getHours|attendance.hoursData',
        ],
    'Monthly Report'=>
        [
            'Monthly Report' => 'attendance.getMonths|attendance.getMonthsUsers',
        ],
    'Leave Types'=>
        [
            'Leave Types List' => 'leave-type.index|leave_type.data',
            'Create' => 'leave-type.create|leave-type.store',
            'Edit' => 'leave-type.edit|leave-type.update',
            'Delete' => 'leave-type.destory',
        ],
    'Apply For Leaves'=>
        [
            'Apply For Leaves List' => 'apply-leave.index|apply-leave.data',
            'Create' => 'apply-leave.create|apply-leave.store',
            'Edit' => 'apply-leave.edit|apply-leave.update',
            'Delete' => 'apply-leave.destory',
            'View' => 'apply-leave.show',
        ],
    'Leave'=>
        [
            'Apply For Leaves List' => 'leave.index|leave.data',
            'View' => 'leave.show',
            'Change Status Of Leave' => 'leave.changeStatus',
        ],

];
