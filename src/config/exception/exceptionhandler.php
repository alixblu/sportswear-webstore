<?php
    function handleException($exception) {
        ApiResponse::customApiResponse($exception->getMessage(),$exception->getCode());
    }
    set_exception_handler('handleException');
?>