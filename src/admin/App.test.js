import { render, screen } from '@testing-library/react';
import App from './App';

describe( 'Admin App', () => {
	it( 'shows the MemberKey heading', () => {
		render( <App /> );

		expect(
			screen.getByRole( 'heading', { name: 'MemberKey' } )
		).toBeInTheDocument();
	} );
} );
