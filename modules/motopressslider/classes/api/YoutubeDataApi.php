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
 * @see https://developers.google.com/youtube/v3/
 */
class MPSLYoutubeDataApi
{
    const TRANSIENT_PREFIX = 'mpsl-youtube-img-';
    const API_BASE_URL = 'https://www.googleapis.com/youtube/v3/videos';
    const API_KEY = 'NVmnFlQRs9kzlAaL-3eUbRbb3mI8gsRs73-UJ7b';

    private static $instance;

    /**
     * @return MPSLYoutubeDataApi
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
                $transient = self::TRANSIENT_PREFIX . $id;
                $one_day = MotoPressSlider::SECONDS_IN_DAY;
                mpsl_set_transient($transient, $thumbnail, $one_day);
            }
        }

        return $thumbnail;
    }

    private function getThumbnailByApi($id)
    {
        if (function_exists('curl_init')) {
            $api_key = str_rot13(self::API_KEY);
            $query_url = mpsl_add_query_args(self::API_BASE_URL, array(
                'key' => $api_key,
                'part' => 'snippet',
                'fields' => 'items/snippet/thumbnails',
                'id' => $id
            ));
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

            if (is_null($data) || isset($data['error']) || !isset($data['items'][0]['snippet']['thumbnails'])) {
                return false;
            }

            $thumbs = $data['items'][0]['snippet']['thumbnails'];
            if (isset($thumbs['maxres']) && isset($thumbs['maxres']['url'])) {
                return $thumbs['maxres']['url'];
            } elseif (isset($thumbs['standart']) && isset($thumbs['standart']['url'])) {
                return $thumbs['standart']['url'];
            } elseif (isset($thumbs['high']) && isset($thumbs['high']['url'])) {
                return $thumbs['high']['url'];
            } elseif (isset($thumbs['medium']) && isset($thumbs['medium']['url'])) {
                return $thumbs['medium']['url'];
            } elseif (isset($thumbs['default']) && isset($thumbs['default']['url'])) {
                return $thumbs['default']['url'];
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function getIdByUrl($url_or_id)
    {
        $regex = '/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/'
                 . '(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))'
                 . '(?P<idbyurl>[^\?&\"\'>]+)|(?P<id>[A-za-z0-9_-]{11})/';
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
