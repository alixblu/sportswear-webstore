<?php
    class ApiResponse{

        public static function customApiResponse($data,$status)
        {
            echo json_encode([
                'status' => $status,
                'data' => $data
            ]);
        }

        public static function customResponse($data, $status, $message = null)
        {

            if ($message === null) {
                $message = $status == 200 ? 'Success' : 'Error';
            }
        
            echo json_encode([
                'status' => $status,
                'message' => $message,
                'data' => $data
            ]);

        }
    }
?>