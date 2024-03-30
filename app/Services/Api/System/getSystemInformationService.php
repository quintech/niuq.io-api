<?php

namespace App\Services\Api\System;

use App\Services\RootService;
use Illuminate\Http\JsonResponse;

class getSystemInformationService extends RootService
{
    // Get the current external IP location of the client
    public function getIp() : JsonResponse
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $myip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
                    $myip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
                } else {
                    if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
                        $myip = $_SERVER['HTTP_FORWARDED_FOR'];
                    } else {
                        if (!empty($_SERVER['HTTP_FORWARDED'])) {
                            $myip = $_SERVER['HTTP_FORWARDED'];
                        } else {
                            if (!empty($_SERVER['REMOTE_ADDR'])) {
                                $myip = $_SERVER['REMOTE_ADDR'];
                            } else {
                                if (!empty($_SERVER['HTTP_VIA'])) {
                                    $myip = $_SERVER['HTTP_VIA'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['message' => trans('web.api.system.getIp'), 'ip' => $myip]);
    }
}
