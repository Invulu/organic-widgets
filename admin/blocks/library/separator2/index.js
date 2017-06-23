function registerBlock() {

	var el = wp.element.createElement;

	wp.blocks.registerBlockType( 'organic-widgets/separator2', {
		title: 'Separator2222',

		icon: 'minus',

		category: 'layout',

		edit: function() {
        return el( 'div', { style: { backgroundColor: '#900', color: '#fff', padding: '20px' } }, 'I am a red block.' );
    },
		save: function() {
        return el( 'div', { style: { backgroundColor: '#900', color: '#fff', padding: '20px' } }, 'I am a red block.' );
		},
	} );
}

registerBlock();
