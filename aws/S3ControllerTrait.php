<?php

namespace ktaris\aws;

use Underscore\Types\Arrays;
use ktaris\aws\S3Trait;

trait S3ControllerTrait
{
    use S3Trait;

    private function getArchivoDeS3ParaMostrarEnNavegador($params)
    {
        $defaultParams = [
            'ResponseContentDisposition' => 'inline',
        ];

        $params = $this->establecerResponseContentTypeHeader($params);

        $params = Arrays::merge($defaultParams, $params);

        return $this->getArchivoDeS3($params);
    }

    private function getArchivoDeS3ParaDescarga()
    {
    }

    private function getArchivoDeS3($params)
    {
        return $this->getS3File($params);
    }
}
