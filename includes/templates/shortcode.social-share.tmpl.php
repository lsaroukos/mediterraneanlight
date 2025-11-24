<?php 
/**
 * render social share icons
 */

$post_id = get_the_ID();

    $url   = urlencode( get_permalink( $post_id ) );
    $title = urlencode( get_the_title( $post_id ) );
?>

    <div class="medlight-social-share">
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" rel="noopener noreferrer">
            <svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path></svg>
        </a><span>|</span><a href="https://pinterest.com/pin/create/button/?url=<?php echo $url; ?>" 
            target="_blank" 
            rel="noopener noreferrer">
            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.04 2C6.54 2 2 6.48 2 12c0 4.09 2.53 7.58 6.12 9.06-.08-.77-.15-1.95.03-2.79.16-.71 1.04-4.5 1.04-4.5s-.26-.52-.26-1.29c0-1.21.7-2.11 1.57-2.11.74 0 1.1.56 1.1 1.23 0 .75-.48 1.86-.73 2.9-.21.87.45 1.58 1.32 1.58 1.58 0 2.8-1.66 2.8-4.06 0-2.12-1.52-3.6-3.69-3.6-2.51 0-3.98 1.89-3.98 3.85 0 .76.29 1.58.65 2.03.07.09.08.17.06.26-.07.28-.23.87-.26.99-.04.17-.14.21-.33.13-1.23-.57-2-2.35-2-3.79 0-3.08 2.24-5.91 6.47-5.91 3.4 0 6.04 2.42 6.04 5.66 0 3.38-2.13 6.1-5.09 6.1-1 0-1.95-.52-2.27-1.13l-.62 2.35c-.22.85-.82 1.92-1.23 2.57.93.29 1.92.45 2.95.45 5.5 0 10.04-4.48 10.04-10S17.54 2 12.04 2z"/></svg>
        </a>
    </div>


