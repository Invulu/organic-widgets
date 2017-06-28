/**
 * WordPress dependencies
 */
// import { Component } from 'gutenberg/element';
// import { Placeholder } from 'components';
// import { __ } from 'i18n';

/**
 * Internal dependencies
 */
// import { registerBlockType } from '../../api';
import { getContentSlideshow } from './data.js';

wp.blocks.registerBlockType( 'organic-widgets/content-slideshow', {
	title: wp.i18n.__( 'Content Slideshow' ),

	icon: 'list-view',

	category: 'widgets',

	defaultAttributes: {
		poststoshow: 5,
	},

	edit: class extends wp.element.Component {
		constructor() {
			super( ...arguments );

			const { poststoshow } = this.props.attributes;

			this.state = {
				contentSlideshow: [],
			};

			this.contentSlideshowRequest = getContentSlideshow( poststoshow );

			this.contentSlideshowRequest
				.then( contentSlideshow => this.setState( { contentSlideshow } ) );
		}

		render() {
			const { contentSlideshow } = this.state;

			if ( ! contentSlideshow.length ) {
				return (
					<wp.components.Placeholder
						icon="update"
						label={ wp.i18n.__( 'Loading latest posts, please wait' ) }
					>
					</wp.components.Placeholder>
				);
			}

			return (
				<div className="blocks-latest-posts">
					<ul>
						{ contentSlideshow.map( ( post, i ) =>
							<li key={ i }><a href={ post.link }>{ post.title.rendered }</a></li>
						) }
					</ul>
				</div>
			);
		}

		componentWillUnmount() {
			if ( this.contentSlideshowRequest.state() === 'pending' ) {
				this.contentSlideshowRequest.abort();
			}
		}
	},

	save() {
		return null;
	},
} );
