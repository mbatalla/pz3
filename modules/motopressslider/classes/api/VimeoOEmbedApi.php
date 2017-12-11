<?php
/**
 * 2014-2016 MotoPress Slider
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPLv2)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl2.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade MotoPress Slider to newer
 * versions in the future.
 *
 * @author    MotoPress <marketing@getmotopress.com>
 * @copyright 2014-2016 MotoPress
 * @license   http://www.gnu.org/licenses/gpl2.html GNU General Public License (GPLv2)
 */

/**
 * @see https://developer.vimeo.com/apis/oembed
 */
class MPSLVimeoOEmbedApi
{
    const TRANSIENT_PREFIX = 'mpsl-vimeo-img-';
    const API_BASE_URL = 'https://vimeo.com/api/oembed.json';

    private static $instance = null;

    /**
     * @return MPSLVimeoOEmbedApi
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getThumbnail($id)
    {
        if (empty($id)) {
            return false;
        }

        $thumbnail = mpsl_get_transient(self::TRANSIENT_PREFIX . $id);
        if ($thumbnail === false) {
            $thumbnail = $this->getThumbnailByAPI($id);
            if ($thumbnail !== false) {
                $tansient = self::TRANSIENT_PREFIX . $id;
                $one_day = MotoPressSlider::SECONDS_IN_DAY;
                mpsl_set_transient($tansient, $thumbnail, $one_day);
            }
        }

        return $thumbnail;
    }

    private function getThumbnailByApi($id)
    {
        if (function_exists('curl_init')) {
            $video_url = $this->getUrlById($id);
            $query_url = mpsl_add_query_args(self::API_BASE_URL, array('url' => $video_url));
            $useragent = mpsl_get_useragent();

            $curl = curl_init($query_url);
            curl_setopt_array($curl, array(
                CURLOPT_TIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERAGENT => $useragent
            ));
            $response_body = curl_exec($curl);
            $error_no = curl_errno($curl);
            curl_close($curl);

            if ($error_no != 0) {
                return false;
            }

            $data = mpsl_json_decode_assoc($response_body);

            if (!is_null($data) && isset($data['thumbnail_url'])) {
                return $data['thumbnail_url'];
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    private function getUrlById($id)
    {
        return 'https://player.vimeo.com/video/' . $id;
    }

    public function getIdByUrl($url_or_id)
    {
        $regex = '/^(?:(?:https?:\/\/|)(?:www\.|player\.|)vimeo.com\/'
                 . '(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)'
                 . '\/videos\/|album\/(?:\d+)\/video\/|video\/|)'
                 . '(?P<idbyurl>\d+)(?:\/|\?|\#\w*|))|(?P<id>\d+)$/';
        preg_match($regex, $url_or_id, $matches);
        if (isset($matches['id'])) {
            return $matches['id'];
        } else {
            if (isset($matches['idbyurl'])) {
                return $matches['idbyurl'];
            } else {
                return '';
            }
        }
    }
}
