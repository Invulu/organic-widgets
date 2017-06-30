/**
 * Returns a Promise with the latest posts or an error on failure.
 *
 * @param   {Number} postsToShow       Number of posts to display.
 *
 * @returns {wp.api.collections.Posts} Returns a Promise with the latest posts.
 */
export function getContentSlideshow( postsToShow = 6 ) {
	const postsCollection = new wp.api.collections.Posts();
	console.log('getContentSlideshow');
	const posts = postsCollection.fetch( {
		data: {
			per_page: postsToShow,
		},
	} );
	console.log(posts);
	return posts;
}
