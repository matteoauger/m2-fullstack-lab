import React       from 'react';
import { NavLink } from 'react-router-dom';

/**
 * Header component.
 * Represents the header section of the react app.
 * Contains all links redirecting to the charts.
 */
export default class Header extends React.Component {
    
    /**
     * Renders the component as JSX 
     */
    render() {
        return <nav id="header">
            <NavLink activeClassName="active"       to="/">Imhotep</NavLink>
            <NavLink activeClassName="active"       to="/timeseries">Evolution des prix</NavLink>
            <NavLink activeClassName="active"       to="/barchart">Nombre de ventes</NavLink>
            <NavLink activeClassName="active"       to="/graphs">Répartition géographique</NavLink>
            <a class="nav-link-ext" href={`https://${document.domain}:8443`}>API</a>
            <a class="nav-link-ext" href={`https://${document.domain}:444`}>Admin</a>
        </nav>
    }
}