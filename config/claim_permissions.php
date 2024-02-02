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
    'Dashboard'=>[
        'View' => 'dashboard.index',
    ],
    'Roles'=>
        [
            'Role List' => 'role.index',
            'Add Role' => 'role.create|role.store',
            'Edit' => 'role.edit|role.update',
            'View' => 'role.show',
            'Set Permissions' => 'role.set-permissions|role.set-permissions.update',
        ],
    'Sub Admins'=>
        [
            'Sub Admin List' => 'sub-admin.index|sub-admin.data',
            'Add Sub Admin' => 'sub-admin.create|sub-admin.store',
            'Edit' => 'sub-admin.edit|sub-admin.update',
            'Status Change' => 'sub-admin.active|sub-admin.inactive',
            'View' => 'sub-admin.show',
            'Delete' => 'sub-admin.destroy',
        ],
    'Client Claims'=>
        [
            'Add Claims' => 'client-claims.create|client-claims.store|client-claims.validateTrackingId',
            'Pending Claims List' => 'client-claims.pendingList|client-claims.pendingList-data',
            'Approved Claims List' => 'client-claims.approvedList|client-claims.approvedList-data',
            'Not Approved Claims List' => 'client-claims.notApprovedList|client-claims.notApprovedList-data',
        ],
    'JoeyCo Claims'=>
        [
            'Add Claims' => 'claims.create|claims.store|claims.validateTrackingId',
            // 'Pending Claims List' => 'claims.pendingList|claims.pendingList-data|claims.reasonUpdate|claims.statusUpdate|claims.uploadImage',
            // 'Approved Claims List' => 'claims.approvedList|claims.approvedList-data|claims.reasonUpdate|claims.statusUpdate|claims.uploadImage',
            // 'Not Approved Claims List' => 'claims.notApprovedList|claims.notApprovedList-data|claims.reasonUpdate|claims.statusUpdate|claims.uploadImage',
            // 'Re-Submitted Claims List' => 'claims.reSubmittedList|claims.reSubmittedList-data|claims.reasonUpdate|claims.statusUpdate|claims.uploadImage',
            //'Update Status'=>'claims.statusUpdate|claims.uploadImage'
            'Pending Claims List' => 'claims.pendingList|claims.pendingList-data',
            'Update Status Pending Claims List' => 'claims.statusUpdate-pending|claims.getReasons|claims.statusUpdate',
             'Pending Order Details'=>'pendingClaimsSearchOrder.show|searchorder.show',

            'Approved Claims List' => 'claims.approvedList|claims.approvedList-data',
            'Update Status Approved Claims List' => 'claims.uploadImage-approved|claims.getReasons|claims.uploadImage',
             'Approved Order Details'=>'approvedClaimsSearchOrder.show|searchorder.show',

            'Not Approved Claims List' => 'claims.notApprovedList|claims.notApprovedList-data',
            'Update Status Not Approved Claims List' => 'claims.uploadImage-reject|claims.getReasons|claims.uploadImage',
             'Not Approved Order Details'=>'rejectClaimsSearchOrder.show|searchorder.show',

            'Re-Submitted Claims List' => 'claims.reSubmittedList|claims.reSubmittedList-data',
            'Update Status Re-Submitted Claims List' => 'claims.statusUpdate-re-submitted|claims.getReasons|claims.statusUpdate',
             'Re-Submitted Order Details'=>'re-submittedClaimsSearchOrder.show|searchorder.show',
        ],
    'Broker Claims'=>
        [
            'Claims List' => 'broker-claims.brookerList|broker-claims.brookerList-data',
            'Joeys Claims List' => 'broker-claims.joeyList|broker-claims.joeyList-data',
            'Report' => 'broker-claims.report|broker-claims.report-data',
        ],
    'JoeyCo Review Claims'=>
        [
            'Broker Claims Review' => 'review.brokerReview|review.brokerReviewData',
            'Joeys Claims Review' => 'review.joeyReview|review.joeyReviewData',
        ],
    'Claim Reasons'=>
        [
            'Claim Reasons List' => 'claim-reason.data|claim-reason.index',
            'Add Claim Reason' => 'claim-reason.create|claim-reason.store',
            'Edit' => 'claim-reason.edit|claim-reason.update',
        ],

    'Setting'=>
        [
            'Setting Main Page' => 'dashboard.index',
            'Change Password' => 'users.change-password',
            'Edit Profile' => 'users.edit-profile',
        ],




];
