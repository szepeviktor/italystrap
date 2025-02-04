<?php
/**
 * Theme configuration file.
 *
 * This is the configuration settings of the theme, you can override the value by filters.
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

$template_content = apply_filters( 'italystrap_template_content_settings',
	array(
		'hide_breadcrumbs'	=> __( 'Hide breadcrumbs', 'italystrap' ),
		'hide_title'		=> __( 'Hide title', 'italystrap' ),
		'hide_meta'			=> __( 'Hide meta info', 'italystrap' ),
		'hide_thumb'		=> __( 'Hide feautured image', 'italystrap' ),
		'hide_figcaption'	=> __( 'Hide figure caption', 'italystrap' ),
		'hide_content'		=> __( 'Hide the content', 'italystrap' ),
		'hide_author'		=> __( 'Hide author box', 'italystrap' ),
		// 'hide_social'		=> __( 'Hide builtin social sharing', 'italystrap' ),
		'hide_comments'		=> __( 'Hide comments', 'italystrap' ),
		'hide_comments_form'=> __( 'Hide comments form', 'italystrap' ),
	)
);

return $template_content;
