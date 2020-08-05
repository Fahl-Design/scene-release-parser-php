<?php

use thcolin\SceneReleaseParser\ReleaseConstants as SrpRc;
use thcolin\SceneReleaseParser\ReleaseMediaInfo;

class ReleaseMediaInfoTest extends \PHPUnit\Framework\TestCase
{
    public function testAnalyseSuccess()
    {
        $elements = [
            'https://samples.mplayerhq.hu/V-codecs/h264/bbc-africa_m720p.mov' => [
                'encoding' => SrpRc::ENCODING_H264,
                'resolution' => SrpRc::RESOLUTION_720P,
                'language' => 'ENGLISH'
            ],
            'https://cinelerra-cv.org/footage/rassegna2.avi' => [
                'encoding' => SrpRc::ENCODING_DIVX,
                'resolution' => SrpRc::RESOLUTION_SD,
                'language' => 'VO'
            ],
            'https://samples.mplayerhq.hu/V-codecs/XVID/old/green.avi' => [
                'encoding' => SrpRc::ENCODING_XVID,
                'resolution' => SrpRc::RESOLUTION_SD,
                'language' => 'VO'
            ],
            'http://samples.mplayerhq.hu/Matroska/x264_no-b-frames.mkv' => [
                'encoding' => SrpRc::ENCODING_X264,
                'resolution' => SrpRc::RESOLUTION_720P,
                'language' => 'VO'
            ],
            'https://s3.amazonaws.com/x265.org/video/Tears_400_x265.mp4' => [
                'encoding' => SrpRc::ENCODING_X265,
                'resolution' => SrpRc::RESOLUTION_1080P,
                'language' => 'VO'
            ],
        ];

        foreach ($elements as $url => $element) {
            $basename = basename($url);

            if (!is_dir(__DIR__ . '/tmp/')) {
                mkdir(__DIR__ . '/tmp/');
            }

            if (!is_file(__DIR__ . '/tmp/' . $basename)) {
                file_put_contents(__DIR__ . '/tmp/' . $basename, fopen($url, 'r'));
            }

            $config = [];

            if (defined('__MEDIAINFO_BIN__')) {
                $config['command'] = __MEDIAINFO_BIN__;
            }

            $release = ReleaseMediaInfo::analyse(__DIR__ . '/tmp/' . $basename, $config);

            $this->assertEquals($element['encoding'], $release->getEncoding(), $url);
            $this->assertEquals($element['resolution'], $release->getResolution(), $url);
            $this->assertEquals($element['language'], $release->getLanguage(), $url);
        }
    }
}