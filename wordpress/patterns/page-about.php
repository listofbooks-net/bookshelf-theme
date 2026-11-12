<?php
/**
 * Title: About page
 * Slug: readerly/page-about
 * Categories: readerly-book-blog, about, page
 * Keywords: about, me, bio, rating key, review policy
 * Block Types: core/post-content
 * Post Types: page
 * Description: An about page for a book blog: who you are, a portrait, how you rate books, your review policy and how to get in touch.
 *
 * @package Readerly
 */

$readerly_rating_key = array(
	array( 5, __( 'A book I’ll press into people’s hands. Rare: about one a year.', 'readerly' ) ),
	array( 4, __( 'Excellent, with a flaw or two I could name.', 'readerly' ) ),
	array( 3, __( 'Good company. Glad I read it, unlikely to reread it.', 'readerly' ) ),
	array( 2, __( 'Something didn’t work for me, and I’ll say what.', 'readerly' ) ),
	array( 1, __( 'I finished it so you don’t have to. I almost never post these.', 'readerly' ) ),
);
?>
<!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":"1.55"}}} -->
<p class="has-large-font-size" style="line-height:1.55"><?php esc_html_e( 'This is where I write about the books I read: slowly, honestly, and usually with a cup of tea going cold beside me.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'I was a school librarian for eleven years. The best part of the job was the five minutes after the bell, when a student would come back to the desk and say “Have you got another one like that?” This site is my attempt to keep having that conversation.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:image {"width":"16rem","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
<figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url( readerly_image_uri( 'avatar-nora.svg' ) ); ?>" alt="<?php echo esc_attr__( 'Portrait of the reviewer', 'readerly' ); ?>" style="border-radius:50%;width:16rem"/><figcaption class="wp-element-caption"><?php esc_html_e( 'Me, approximately.', 'readerly' ); ?></figcaption></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'I read about sixty books a year. Most of them are literary fiction, quieter fantasy, essays and the occasional slim book of poems. I don’t review everything I read; the full list, abandoned books included, is in my reading log.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'How I rate books', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<?php foreach ( $readerly_rating_key as $readerly_key ) : ?>
<?php echo readerly_rating_block( $readerly_key[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside the helper. ?>

<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"0.25rem"}}}} -->
<p style="margin-top:0.25rem"><?php echo esc_html( $readerly_key[1] ); ?></p>
<!-- /wp:paragraph -->

<?php endforeach; ?>
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Review copies', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'I’m happy to receive review copies, but I can’t promise a review, and I’ll always say when a book was sent to me. I don’t accept payment for reviews, and I don’t run sponsored posts.', 'readerly' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p><?php esc_html_e( '“I finished it at two in the morning and then sat in the dark for a while, which is the highest praise I know how to give.”', 'readerly' ); ?></p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->

<!-- wp:separator {"className":"is-style-dots"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-dots"/>
<!-- /wp:separator -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Say hello', 'readerly' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php
printf(
	/* translators: %s: email address, as a link. */
	esc_html__( 'Recommendations, disagreements and photos of your own teetering piles are all welcome at %s.', 'readerly' ),
	'<a href="mailto:hello@example.com">hello@example.com</a>'
);
?></p>
<!-- /wp:paragraph -->
