import React from 'react';
import { NavLink } from 'react-router-dom';

import './header.css';

/**
 * Header component.
 * Represents the header section of the react app.
 * Contains all links redirecting to the charts.
 */
export default function Header() {
    return (
        <nav id='header'>
            <NavLink to='/' exact     className='home'>Imhotep</NavLink>
            <NavLink to='/timeseries' activeClassName='active'>Evolution des prix</NavLink>
            <NavLink to='/barchart'   activeClassName='active'>Nombre de ventes</NavLink>
            <NavLink to='/piechart'     activeClassName='active'>Répartition géographique</NavLink>
            <a href={`https://${document.domain}:8443`}>API</a>
        </nav>
    );
}