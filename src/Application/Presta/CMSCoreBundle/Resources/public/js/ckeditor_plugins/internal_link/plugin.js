/**
 * Link to PrestaCMS internal pages
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
CKEDITOR.scriptLoader.load('/admin/cms/page/wysiwyg_list');
CKEDITOR.plugins.add(
    'internal_link',
    {
        icons: 'internal_link',
        init: function( editor ) {
            editor.addCommand( 'internalLinkDialog', new CKEDITOR.dialogCommand( 'internalLinkDialog' ) );
            editor.ui.addButton( 'InternalLink', {
                label: 'Internal Link',
                command: 'internalLinkDialog',
                toolbar: 'links',
                icon: CKEDITOR.plugins.getPath('internal_link') + 'icons/internal_link.png'
            });

            CKEDITOR.dialog.add( 'internalLinkDialog', this.path + 'dialogs/internal_link.js' );
        }
    }
);