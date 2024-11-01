<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wp-styles.de
 * @since      0.1.0
 *
 * @package    Wp_Business_Essentials
 * @subpackage Wp_Business_Essentials/public/partials
 */

if ( ! function_exists( 'wpbe_team' ) ) {
	/**
	 * @param integer $team_id
	 * @param array $args
	 *
	 * @return string
	 */
	function wpbe_team(
		$team_id,
		$args = array( 'columns' => 4, 'size' => 'medium' )
	) {
		$team = get_posts(
			array(
				'showposts' => - 1,
				'post_type' => 'wpbe_team_member',
				'orderby'   => 'menu_order',
				'order'     => 'ASC',
				'tax_query' => array(
					array(
						'taxonomy' => 'wpbe_team',
						'fields'   => 'term_id',
						'terms'    => $team_id
					)
				)
			)
		);

		$html = '';
		$html .= '<div class="team">';

		foreach ( $team as $team_member ) {
			$html .= '<div class="team-item team-columns-' . $args['columns'] . '">';
			$html .= wpbe_team_member( $team_member, $args );
			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}
}

if ( ! function_exists( 'wpbe_team_member' ) ) {
	/**
	 * @param int|WP_Post|null $team_member
	 * @param array $args
	 *
	 * @return string
	 */
	function wpbe_team_member( $team_member, $args = array() ) {
		$team_member  = get_post( $team_member );
		$current_user = get_current_user_id();

		$html = '';
		$html .= '<div class="team-member">';

		$html .= '<div class="team-member-row team-member-row-thumbnail">';
		if ( has_post_thumbnail( $team_member->ID ) ) {
			$html .= get_the_post_thumbnail(
				$team_member->ID,
				$args['size'],
				array( 'team-member-thumbnail' )
			);
		}
		$html .= '</div>';

		$html .= '<div class="team-member-block team-member-block-heading">';
		$html .= '<div class="team-member-row">';
		$html .= sprintf(
			'<div class="team-member-title">%s</div>',
			esc_html( $team_member->post_title )
		);
		$html .= '</div>';

		$html .= '<div class="team-member-row">';
		$html .= wpbe_get_team_member_position(
			$team_member->ID,
			'wpbe-team-member-position'
		);
		$html .= '</div>';
		$html .= '</div>';

		$html .= '<div class="team-member-block team-member-block-contact">';
		$html .= '<div class="team-member-row">';
		$html .= wpbe_get_team_member_mobile(
			$team_member->ID,
			'wpbe-team-member-mobile'
		);
		$html .= '</div>';

		$html .= '<div class="team-member-row">';
		$html .= wpbe_get_team_member_email(
			$team_member->ID,
			'wpbe-team-member-email'
		);
		$html .= '</div>';

		$html .= '<div class="team-member-row">';
		$html .= wpbe_get_team_member_url(
			$team_member->ID,
			'wpbe-team-member-url'
		);
		$html .= '</div>';
		$html .= '</div>';

		if ( user_can( $current_user, 'editor' ) || user_can( $current_user, 'administrator' ) ) {
			$html .= '<div class="team-member-block">';
			$html .= '<div class="team-member-row">';
			$html .= sprintf( '<a href="%swp-admin/post.php?post=%s&action=edit">%s</a>',
				esc_url( home_url( '/' ) ),
				$team_member->ID,
				__( 'Edit', 'wp-business-essentials' )
			);
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '</div>';

		return $html;
	}
}

if ( ! function_exists( 'wpbe_get_team_member_meta' ) ) {
	function wpbe_get_team_member_meta( $id, $key ) {
		$meta = esc_html( get_post_meta( $id, $key, true ) );
		if ( empty( $meta ) ) {
			return false;
		}

		return $meta;
	}
}

if ( ! function_exists( 'wpbe_get_team_member_position' ) ) {
	function wpbe_get_team_member_position( $id, $key ) {
		$position = wpbe_get_team_member_meta( $id, $key );
		$html     = false;

		if ( $position ) {
			$html = sprintf(
				'<div class="team-member-position">%s</div>',
				$position
			);
		}

		return $html;
	}
}

if ( ! function_exists( 'wpbe_get_team_member_mobile' ) ) {
	function wpbe_get_team_member_mobile( $id, $key ) {
		$mobile = wpbe_get_team_member_meta( $id, $key );
		$html   = false;

		if ( $mobile ) {
			$html = sprintf(
				'<div class="team-member-mobile">%s</div>',
				$mobile
			);
		}

		return $html;
	}
}

if ( ! function_exists( 'wpbe_get_team_member_email' ) ) {
	function wpbe_get_team_member_email( $id, $key ) {
		$email = wpbe_get_team_member_meta( $id, $key );
		$html  = false;

		if ( $email ) {
			$html = sprintf(
				'<a href="mailto:%s" class="team-member-email">%s</a>',
				$email,
				$email
			);
		}

		return $html;
	}
}

if ( ! function_exists( 'wpbe_get_team_member_url' ) ) {
	function wpbe_get_team_member_url( $id, $key ) {
		$url  = wpbe_get_team_member_meta( $id, $key );
		$html = false;

		if ( $url ) {
			$url_label = preg_replace( "(^https?://)", "", $url );
			$html      = sprintf(
				'<a href="%s" class="team-member-url">%s</a>',
				$url,
				$url_label
			);
		}

		return $html;
	}
}