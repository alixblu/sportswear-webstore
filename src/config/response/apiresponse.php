<?php
if (!class_exists('ApiResponse')) {
    class ApiResponse{

        public static function customApiResponse($data,$status)
        {
            echo json_encode([
                'status' => $status,
                'data' => $data
            ]);
        }
    }
}
?>