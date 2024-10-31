<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\EmailConfiguration;
use App\Models\SecretCrediential;


class AdminPanelSetupController extends Controller
{

   public function general_setting(Request $request)
   {
   $dataInfo = EmailConfiguration::find(1);
   if(!empty($dataInfo)){
    $dataInfo->port = $request->port;
    $dataInfo->host = $request->host;
    $dataInfo->user_name = $request->user_name;
    $dataInfo->password = $request->password;
    $dataInfo->sender_email = $request->sender_email;
    $dataInfo->sender_name = $request->sender_name;
    $dataInfo->driver = $request->driver;
    $dataInfo->encryption = $request->encryption;
  
    $dataInfo->save(); 

    if($dataInfo){


        $path = base_path('.env');
        // dd($path);
        $searchArray = array('MAIL_MAILER=' . env('MAIL_MAILER') . '' ,'MAIL_HOST=' . env('MAIL_HOST') . '' , 'MAIL_PORT=' . env('MAIL_PORT') . '' , 'MAIL_USERNAME=' . env('MAIL_USERNAME') . '' , 'MAIL_PASSWORD=' . env('MAIL_PASSWORD') . '' , 'MAIL_FROM_NAME=' . env('MAIL_FROM_NAME') . '' , 'MAIL_FROM_EMAIL=' . env('MAIL_FROM_EMAIL') . '' , 'MAIL_ENCRYPTION=' . env('MAIL_ENCRYPTION') . '' );
        //    return $searchArray;

        $replaceArray = array('MAIL_MAILER=' . $request['driver'] . '' ,'MAIL_HOST=' . $request['host'] . '' , 'MAIL_PORT=' . $request['port'] . '', 'MAIL_USERNAME=' . $request['user_name'] . '' , 'MAIL_PASSWORD=' . $request['password'] . ''  , 'MAIL_FROM_NAME=' . $request['sender_name'] . '' , 'MAIL_FROM_EMAIL=' . $request['sender_email'] . '', 'MAIL_ENCRYPTION=' . $request['encryption'] . '' );
        // return $replaceArray;
        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Email Setting Update Successfully .',

        ];

    }else{
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Failed To Update Email Setting, Try Again.'
        ];

    }
   

   }else{
    $dataInfo = new EmailConfiguration();
    $dataInfo->port = $request->port;
    $dataInfo->host = $request->host;
    $dataInfo->user_name = $request->user_name;
    $dataInfo->password = $request->password;
    $dataInfo->sender_email = $request->sender_email;
    $dataInfo->sender_name = $request->sender_name;
    $dataInfo->driver = $request->driver;
    $dataInfo->encryption = $request->encryption;
  
    if($dataInfo->save()){
        $responseData =[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'ms'=>'Email Setting Update successfully',
            'errMsg'=>null,
        ];

    }else{
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'ms'=>null,
            'errMsg'=>'Failed To Update Email Setting. ',
        ];
    }

   }
   return response()->json($responseData,200);
   } 

   public function getEmailInformation()
   {
    $dataInfo = EmailConfiguration::find(1);
    $flag=(!empty($dataInfo)) ? true:false;
    $responseData=[
                'errMsgFlag'=>!$flag,
                'msgFlag'=>$flag,
                'msg'=>null,
                'errMsg'=>null,
                'dataInfo'=>$dataInfo,
            ];

    return response()->json($responseData,200);
   
   }

   public function getInformation(Request $request)
   {
    $dataList = GeneralSetting::first();
    return response()->json($dataList,200);
   }

   public function google_auth_setting(Request $request)
   {
   $dataInfo = SecretCrediential::find(1);
   if(!empty($dataInfo)){
    $dataInfo->google_client_id = $request->googleClientId;
    $dataInfo->google_client_secret = $request->googleClientSecret;
    $dataInfo->google_redirect_url = $request->googleRedirectUrl;


  
    $dataInfo->save(); 

    if($dataInfo){


        $path = base_path('.env');
        // dd($path);
        $searchArray = array('GOOGLE_CLIENT_ID=' . env('GOOGLE_CLIENT_ID') . '' ,'GOOGLE_CLIENT_SECRET=' . env('GOOGLE_CLIENT_SECRET') . '' , 'GOOGLE_REDIRECT_URI=' . env('GOOGLE_REDIRECT_URI') . ''  );
        //    return $searchArray;

        $replaceArray = array('GOOGLE_CLIENT_ID=' . $request['googleClientId'] . '' ,'GOOGLE_CLIENT_SECRET=' . $request['googleClientSecret'] . '' , 'GOOGLE_REDIRECT_URI=' . $request['googleRedirectUrl'] . '' );
        // return $replaceArray;
        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Email Setting Update Successfully .',

        ];

    }else{
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Failed To Update Email Setting, Try Again.'
        ];

    }
   

   }else{
    $dataInfo = new SecretCrediential();
    $dataInfo->google_client_id = $request->googleClientId;
    $dataInfo->google_client_secret = $request->googleClientSecret;
    $dataInfo->google_redirect_url = $request->googleRedirectUrl;
  
  
    if($dataInfo->save()){
        $responseData =[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'ms'=>'Email Setting Update successfully',
            'errMsg'=>null,
        ];

    }else{
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'ms'=>null,
            'errMsg'=>'Failed To Update Email Setting. ',
        ];
    }

   }
   return response()->json($responseData,200);
   } 

   public function getGoogleAuthInfo()
   {
    $dataInfo = SecretCrediential::find(1);
    $flag=(!empty($dataInfo)) ? true:false;
    $responseData=[
                'errMsgFlag'=>!$flag,
                'msgFlag'=>$flag,
                'msg'=>null,
                'errMsg'=>null,
                'dataInfo'=>$dataInfo,
            ];

    return response()->json($responseData,200);
   
   }

   public function facebook_auth_setting(Request $request)
   {
   $dataInfo = SecretCrediential::find(1);
   if(!empty($dataInfo)){
    $dataInfo->facebook_client_id = $request->facebookClientId;
    $dataInfo->facebook_client_secret = $request->facebookClientSecret;
    $dataInfo->facebook_redirect_url = $request->facebookRedirectUrl;


  
    $dataInfo->save(); 

    if($dataInfo){


        $path = base_path('.env');
        // dd($path);
        $searchArray = array('FACEBOOK_CLIENT_ID=' . env('FACEBOOK_CLIENT_ID') . '' ,'FACEBOOK_CLIENT_SECRET=' . env('FACEBOOK_CLIENT_SECRET') . '' , 'FACEBOOK_REDIRECT_URI=' . env('FACEBOOK_REDIRECT_URI') . ''  );
        //    return $searchArray;

        $replaceArray = array('FACEBOOK_CLIENT_ID=' . $request['facebookClientId'] . '' ,'FACEBOOK_CLIENT_SECRET=' . $request['facebookClientSecret'] . '' , 'FACEBOOK_REDIRECT_URI=' . $request['facebookRedirectUrl'] . '' );
        // return $replaceArray;
        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Email Setting Update Successfully .',

        ];

    }else{
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Failed To Update Email Setting, Try Again.'
        ];

    }
   

   }else{
    $dataInfo = new SecretCrediential();
    $dataInfo->facebook_client_id = $request->facebookClientId;
    $dataInfo->facebook_client_secret = $request->facebookClientSecret;
    $dataInfo->facebook_redirect_url = $request->facebookRedirectUrl;
  
  
    if($dataInfo->save()){
        $responseData =[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'ms'=>'Email Setting Update successfully',
            'errMsg'=>null,
        ];

    }else{
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'ms'=>null,
            'errMsg'=>'Failed To Update Email Setting. ',
        ];
    }

   }
   return response()->json($responseData,200);
   } 

   public function getFacebookAuthInfo()
   {
    $dataInfo = SecretCrediential::find(1);
    $flag=(!empty($dataInfo)) ? true:false;
    $responseData=[
                'errMsgFlag'=>!$flag,
                'msgFlag'=>$flag,
                'msg'=>null,
                'errMsg'=>null,
                'dataInfo'=>$dataInfo,
            ];

    return response()->json($responseData,200);
   
   }

   
   public function pusher_setting(Request $request)
   {
   $dataInfo = SecretCrediential::find(1);
   if(!empty($dataInfo)){
    $dataInfo->pusher_app_id = $request->pusherAppId;
    $dataInfo->	pusher_app_key = $request->pusherAppKey;
    $dataInfo->pusher_app_secret = $request->pusherAppSecret;
    $dataInfo->	pusher_app_cluster = $request->pusherAppCluster;
    $dataInfo->broadcast_driver= $request->broadcastDriver;


  
    $dataInfo->save(); 

    if($dataInfo){


        $path = base_path('.env');
        // dd($path);
        $searchArray = array('PUSHER_APP_ID=' . env('PUSHER_APP_ID') . '' ,'PUSHER_APP_KEY=' . env('PUSHER_APP_KEY') . '' , 'PUSHER_APP_SECRET=' . env('PUSHER_APP_SECRET') . '', 'PUSHER_APP_CLUSTER=' . env('PUSHER_APP_CLUSTER') . '', 'BROADCAST_DRIVER=' . env('BROADCAST_DRIVER') . ''  );
        //    return $searchArray;

        $replaceArray = array('PUSHER_APP_ID=' . $request['pusherAppId'] . '' ,'PUSHER_APP_KEY=' . $request['pusherAppKey'] . '' , 'PUSHER_APP_SECRET=' . $request['pusherAppSecret'] . '' , 'PUSHER_APP_CLUSTER=' . $request['pusherAppCluster'] . '', 'BROADCAST_DRIVER=' . $request['broadcastDriver'] . '');
        // return $replaceArray;
        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Email Setting Update Successfully .',

        ];

    }else{
        $responseData=[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'msg'=>null,
            'errMsg'=>'Failed To Update Email Setting, Try Again.'
        ];

    }
   

   }else{
    $dataInfo = new SecretCrediential();
    $dataInfo->pusher_app_id = $request->pusherAppId;
    $dataInfo->	pusher_app_key = $request->pusherAppKey;
    $dataInfo->pusher_app_secret = $request->pusherAppSecret;
    $dataInfo->	pusher_app_cluster = $request->pusherAppCluster;
    $dataInfo->broadcast_driver= $request->broadcastDriver;
  
  
    if($dataInfo->save()){
        $responseData =[
            'errMsgFlag'=>false,
            'msgFlag'=>true,
            'ms'=>'Email Setting Update successfully',
            'errMsg'=>null,
        ];

    }else{
        $responseData =[
            'errMsgFlag'=>true,
            'msgFlag'=>false,
            'ms'=>null,
            'errMsg'=>'Failed To Update Email Setting. ',
        ];
    }

   }
   return response()->json($responseData,200);
   } 

   public function getPusherInfo()
   {
    $dataInfo = SecretCrediential::find(1);
    $flag=(!empty($dataInfo)) ? true:false;
    $responseData=[
                'errMsgFlag'=>!$flag,
                'msgFlag'=>$flag,
                'msg'=>null,
                'errMsg'=>null,
                'dataInfo'=>$dataInfo,
            ];

    return response()->json($responseData,200);
   
   }

  
}
