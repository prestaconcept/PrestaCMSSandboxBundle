/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
CKEDITOR.dialog.add( 'internalLinkDialog', function ( editor ) {
    return {
        title: 'PrestaCMS Internal Links',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-cms-pages',
                label: 'CMS Pages',
                elements: [
                    {
                        type: 'select',
                        id: 'internal-link',
                        label: 'Page',
                        items: WYSIWYG_INTERNAL_PAGES,
                        validate: CKEDITOR.dialog.validate.notEmpty( "Page field cannot be empty" ),
                        setup: function( element ) {
                            this.setValue( element.getAttribute( "href" ) );
                        },
                        commit: function( element ) {
                            element.setAttribute( "href", this.getValue() );
                        }
                    }
                ]
            }
        ],
        onShow: function() {
            var selection = editor.getSelection();
            var element = selection.getStartElement();

            if ( element ) {
                element = element.getAscendant( 'a', true );
            }

            if ( !element || element.getName() != 'a' ) {
                element = editor.document.createElement( 'a' );
                element.setText(selection.getSelectedText());
                this.insertMode = true;
            } else {
                this.insertMode = false;
            }

            this.element = element;
        },
        onOk: function() {
            var dialog = this;
            var link = dialog.element;

            dialog.commitContent( link );

            if ( dialog.insertMode ) {
                editor.insertElement( link );
            }
        }
    };
});
