<?php
namespace App\Service;
 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException as  RequestException;
class SpotifyAPI 
{
    
    private static $instance;
    private $client_id;
    private $client_secret;
    private $token;
    private $expiration;
    private $http_client;

    public function __construct( $credentials ){
        $this->client_id =  $credentials['client_id'];
        $this->client_secret = $credentials['client_secret'];
        $this->http_client =  new Client([ 'timeout'  => 2.0]);
    } 

    static function getInstance($spotify_credentials){
        if(self::$instance == null){
            self::$instance = new SpotifyAPI($spotify_credentials);
        }
        return self::$instance;
    }


    public function login(){
        $url = "https://accounts.spotify.com/api/token";
        try{
            $response = $this->http_client->request("POST", $url, [
                    'headers' => array("Authorization" => "Basic ".\base64_encode($this->client_id.":".$this->client_secret),
                    'Content-Type' => 'application/x-www-form-urlencoded'),
                    'form_params' => [
                        'grant_type' => "client_credentials",
                    ] 
                ]
            );

            if($response->getStatusCode() == 200){
                $res = \json_decode($response->getBody());
                $this->token = $res->access_token;
                $this->expiration = (new \DateTime())->getTimestamp() + ($res->expires_in * 1000);
            }
        }catch(\Exception $e){
            throw $e;
        }
 
        return true;
    }



    public function albumSerialize($album){
        return array(
            'name' => $album->name,
            'released' => $album->release_date,
            "tracks"=> $album->total_tracks,
            "cover" =>  $album->images[0]
        );
    }


    public function artistAlbumsSerialize( $items ){
        $albums_serialized = array();
        $albums_serialized = array_map(function($value){
            return $this->albumSerialize($value);
        }, $items);
        return $albums_serialized;
    }


    public function search($artist_name){
        try{
       
            $url_search = "https://api.spotify.com/v1/search"; 
            $url_albums = "https://api.spotify.com/v1/artists/IDARTIST/albums";
            if(!$this->token || ((new \DateTime())->getTimestamp()  >= $this->expiration )){
                $this->login();
            }

            //search artist
            $response = $this->http_client->request('GET', $url_search,  [
                'headers' => array("Authorization" => "Bearer ".$this->token),
                'query' => ['q' => $artist_name, 'type' => 'artist']
                ]
            );

            $res = \json_decode((string)$response->getBody()->getContents());
            if(isset($res->artists->items[0])){
                $artist_id = $res->artists->items[0]->id;            
                $url_albums = \str_replace("IDARTIST",$artist_id,$url_albums);
                
                //search artist's albums
                $response2 = $this->http_client->request('GET', $url_albums,  [
                    'headers' => array("Authorization" => "Bearer ".$this->token),
                    ]
                );
                $serialized = \json_decode((string)$response2->getBody()->getContents());
                return $this->artistAlbumsSerialize($serialized->items);
            }else{
                return array( );    
            }
            
 
        }catch(\Exception $e){
            throw new \Exception("Spotify API Conection Error");
        }
    }

}
