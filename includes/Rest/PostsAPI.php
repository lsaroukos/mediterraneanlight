<?php 
/**
 * Posts API
 */
namespace MedLight\Rest;

use MedLight\Utils\WCUtils;

if( !class_exists('MedLight\Rest\PostsAPI') ){
class PostsAPI extends RestAPI
{

    /**
	 * Override default route
	 * @var string
	 */
	const route = 'posts';

    /**
     * API Endpoint, resolves on /wp-json/medlight/v1/
     * 
     * register controller routes
     */
    public function register_api_routes()
    {
        // resolves at /wp-json/medlight/v1/posts/related/
        register_rest_route( $this->get_namespace(), $this->get_route("related/(?P<pid>\d+)") ,[
            [
                'methods'   =>  \WP_Rest_Server::READABLE,
                'callback'  =>  [$this, 'get_related_posts'],
                'args'      =>  [ 
                    "pid" => [
                        'validate_callback' => function ($param, $request, $key) {
                            return is_numeric($param);
                        }
                    ],
                ], 
                'permission_callback'    => [$this,'check_nonce'],  
            ]
        ]);

    }


    /**
     * fetch related posts from database
     */
    function get_related_posts( $request ) {

        $post_id = $request->get_param('pid');

        $cats = wp_get_post_categories($post_id);

        $args = [
            'category__in' => $cats,
            'post__not_in' => [$post_id],
            // 'posts_per_page' => 4,
        ];

        $related_posts_raw = get_posts($args);

        // Add thumbnail to each post
        $related_posts = [];
        foreach ($related_posts_raw as &$post) {
            $related_posts[] = [
                'ID'    =>  $post->ID,
                'date'  =>  $post->post_date,
                'title' =>  $post->post_title,
                'excerpt'=> $post->post_excerpt,
                'link'  =>  get_post_permalink($post->ID),
                'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium'),
            ];
        }

        return $this->response([
            'status'    => 'success',
            'posts'     =>  $related_posts
        ]);exit;

        exit;

    }


}
}