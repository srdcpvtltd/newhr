<?php

namespace App\Helpers\SMPush;

use App\Helpers\AppHelper;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpseclib3\Math\PrimeField\Integer;

class SMPushHelper
{
    const IS_ACTIVE = 1;

    public static function getAllCompanyUsersFCMTokens($deviceType): array
    {
        return User::where([
                ['device_type', $deviceType],
                ['is_active', self::IS_ACTIVE],
                ['status', 'verified']
            ])
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token', 'id')
            ->toArray();
    }

    public static function getEmployeeFCMTokensForSending(array $userIds, $deviceType)
    {
        return User::where([
                ['device_type', $deviceType],
                ['is_active', self::IS_ACTIVE],
                ['status', 'verified']
            ])
            ->whereIn('id',$userIds)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token', 'id')
            ->toArray();
    }

    public static function getUserFCMToken($userId,$deviceType): array
    {
        return User::where([
                ['id', $userId],
                ['device_type', $deviceType],
                ['is_active', self::IS_ACTIVE],
                ['status', 'verified']
            ])
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token', 'id')
            ->toArray();
    }

    public static function getFCMTokensWithGivenRoleIds($deviceType,$roleIds):array
    {
        return User::where([
            ['device_type', $deviceType],
            ['is_active', self::IS_ACTIVE],
            ['status', 'verified']
        ])
        ->whereHas('role', fn($query) => $query->whereIn('id', $roleIds))
        ->whereNotNull('fcm_token')
        ->pluck('fcm_token', 'id')
        ->toArray();
    }

    public static function sendPush(string $title, string $description, array $data=[]): void
    {
        SMPushNotification::smSend(
            isAndroid: true,
            title: $title,
            message: Str::limit($description, 100, '...'),
            type: SMPushNotification::C_TYPE_NOTIFICATION,
            data: [
                'title'=>$title,
                'message'=> Str::limit($description, 3700, '...'),
            ],
            recipients: self::getAllCompanyUsersFCMTokens(User::ANDROID)
        );

        SMPushNotification::smSend(
            isAndroid: false,
            title: $title,
            message: Str::limit($description, 100, '...'),
            type: SMPushNotification::C_TYPE_NOTIFICATION,
            data: [
                'title'=>$title,
                'message'=> Str::limit($description, 3700, '...')
            ],
            recipients: self::getAllCompanyUsersFCMTokens(User::IOS)
        );
    }

    public static function sendLeaveStatusNotification(string $title, string $description,$userId)
    {
         SMPushNotification::smSend(
            isAndroid: true,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_LEAVE,
            data: [
                'title'=>$title,
                'message'=> $description
            ],
            recipients: self::getUserFCMToken($userId,User::ANDROID)
        );

        SMPushNotification::smSend(
            isAndroid: false,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_LEAVE,
            data: [
                'title'=>$title,
                'message'=> $description
            ],
            recipients: self::getUserFCMToken($userId,User::IOS)
        );
    }

    public static function sendNoticeNotification(string $title,
                                                  string $description,
                                                  array $userIds,
                                                  bool $teamMeeting = false,
                                                  $id=''
    ): void
    {
        SMPushNotification::smSend(
            isAndroid: true,
            title: $title,
            message: Str::limit($description, 100, '...'),
            type: $teamMeeting ? SMPushNotification::C_TYPE_TEAM_MEETING : SMPushNotification::C_TYPE_NOTICE  ,
            data: [
                'title'=>$title,
                'message'=> Str::limit($description, 3700, '...'),
                'id'=> $id
            ],
            recipients: self::getEmployeeFCMTokensForSending($userIds,User::ANDROID)
        );

        SMPushNotification::smSend(
            isAndroid: false,
            title: $title,
            message: Str::limit($description, 100, '...'),
            type: $teamMeeting ? SMPushNotification::C_TYPE_TEAM_MEETING : SMPushNotification::C_TYPE_NOTICE  ,
            data: [
                'title'=>$title,
                'message'=> Str::limit($description, 3700, '...'),
                'id'=> $id
            ],
            recipients: self::getEmployeeFCMTokensForSending($userIds,User::IOS)
        );
    }

    public static function sendSupportNotification(string $title,
                                                  string $description,
                                                  array $userIds,
                                                         $id=''
    ): void
    {
        SMPushNotification::smSend(
            isAndroid: true,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_SUPPORT,
            data: [
                'title'=> $title,
                'message'=> $description,
                'id'=> $id
            ],
            recipients: self::getEmployeeFCMTokensForSending($userIds,User::ANDROID)
        );

        SMPushNotification::smSend(
            isAndroid: false,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_SUPPORT ,
            data: [
                'title'=>$title,
                'message'=> $description,
                'id'=> $id
            ],
            recipients: self::getEmployeeFCMTokensForSending($userIds,User::IOS)
        );
    }

    public static function sendProjectManagementNotification(string $title,
                                                   string $description,
                                                   array $userIds,
                                                          $id=''
    ): void
    {
        SMPushNotification::smSend(
            isAndroid: true,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_PROJECT_MANAGEMENT,
            data: [
                'title'=>$title,
                'message'=> $description,
                'id'=> $id ?? 0
            ],
            recipients: self::getEmployeeFCMTokensForSending($userIds,User::ANDROID)
        );

        SMPushNotification::smSend(
            isAndroid: false,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_PROJECT_MANAGEMENT,
            data: [
                'title'=>$title,
                'message'=> $description,
                'id'=> $id ?? 0
            ],
            recipients: self::getEmployeeFCMTokensForSending($userIds,User::IOS)
        );
    }

    public static function sendNotificationToAuthorizedUsers(string $title,
                                                             string $description,
                                                             array $roleIds)
    {
        $data = [
            'title' => $title,
            'message' => $description
        ];
        self::sendNotificationToRecipients($title, $description, $data, $roleIds,User::ANDROID);
        self::sendNotificationToRecipients($title, $description, $data, $roleIds,User::IOS);
    }

    private static function sendNotificationToRecipients(string $title,
                                                         string $message,
                                                         array $data,
                                                         array $roleIds,
                                                         string $deviceType)
    {
        $recipients = self::getFCMTokensWithGivenRoleIds($deviceType,$roleIds);
        SMPushNotification::smSend(
            isAndroid: ($deviceType === User::ANDROID),
            title: $title,
            message: $message,
            type: SMPushNotification::C_TYPE_NORMAL,
            data: $data,
            recipients: $recipients
        );
    }

    public static function sendPushNotification(string $title,
                                                string $conversation_id,
                                                string $message,
                                                string $type,
                                                array $usernames,
                                                string $project_id,
    ): void
    {

        SMPushNotification::smSend(
            isAndroid: true,
            title: $title,
            message: Str::limit($message, 100, '...'),
            type: $type,
            data: [
                'title' => $title,
                'conversation_id' => $conversation_id,
                'sender_name' => auth()->user()->name,
                'sender_image' => auth()->user()->image ? asset(User::AVATAR_UPLOAD_PATH . auth()->user()->image) : asset('assets/images/img.png'),
                'message' => $message,
                'type' => $type,
                'project_id' => $project_id,
                'sender_username' => auth()->user()->username,
            ],
            recipients: $type == 'group_chat' ? self::getEmployeeFCMTokensForSending($usernames, User::ANDROID) : self::getEmployeeByUsernameFCMTokensForSending($usernames, User::ANDROID)
        );

        SMPushNotification::smSend(
            isAndroid: false,
            title: $title,
            message: Str::limit($message, 100, '...'),
            type: $type,
            data: [
                'title'=>$title,
                'conversation_id'=>$conversation_id,
                'message'=>$message,
                'type'=>$type,
                'project_id'=>$project_id,
            ],
            recipients: $type == 'group_chat' ? self::getEmployeeFCMTokensForSending($usernames, User::IOS) : self::getEmployeeByUsernameFCMTokensForSending($usernames, User::IOS)

        );
    }

    public static function getEmployeeByUsernameFCMTokensForSending(array $usernames, $deviceType)
    {

        return User::where([
            ['device_type', $deviceType],
            ['is_active', self::IS_ACTIVE],
            ['status', 'verified']
        ])
            ->whereIn('username',$usernames)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token', 'id')
            ->toArray();
    }

    public static function sendAdvanceSalaryNotification(string $title, string $description,$userId)
    {
        SMPushNotification::smSend(
            isAndroid: true,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_ADVANCE_SALARY,
            data: [
                'title'=>$title,
                'message'=> $description
            ],
            recipients: self::getUserFCMToken($userId,User::ANDROID)
        );

        SMPushNotification::smSend(
            isAndroid: false,
            title: $title,
            message: $description,
            type: SMPushNotification::C_TYPE_ADVANCE_SALARY,
            data: [
                'title'=>$title,
                'message'=> $description
            ],
            recipients: self::getUserFCMToken($userId,User::IOS)
        );
    }


    public static function sendNotificationToDepartmentHead(string $title,
                                                             string $description,
                                                             int $userId)
    {
        $data = [
            'title' => $title,
            'message' => $description
        ];
        self::sendNotificationToUser($title, $description, $data, $userId,User::ANDROID);
        self::sendNotificationToUser($title, $description, $data, $userId,User::IOS);
    }

    private static function sendNotificationToUser(string $title,
                                                         string $message,
                                                         array $data,
                                                         int $userId,
                                                         string $deviceType)
    {
        $recipients = self::getFCMTokensWithGivenUserId($deviceType,$userId);
        SMPushNotification::smSend(
            isAndroid: ($deviceType === User::ANDROID),
            title: $title,
            message: $message,
            type: SMPushNotification::C_TYPE_NORMAL,
            data: $data,
            recipients: $recipients
        );
    }

    public static function getFCMTokensWithGivenUserId($deviceType,$userId):array
    {
        return User::where([
            ['device_type', $deviceType],
            ['is_active', self::IS_ACTIVE],
            ['status', 'verified']
        ])
            ->where('id',$userId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token', 'id')
            ->toArray();
    }

}
