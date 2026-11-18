import { createRoot } from '@wordpress/element';
import App from './App';

const root = document.getElementById( 'memberkey-admin' );

if ( root ) {
	createRoot( root ).render( <App /> );
}
