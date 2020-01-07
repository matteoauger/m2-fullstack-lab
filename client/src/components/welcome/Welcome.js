import React from 'react';
import './welcome.css';

export default class Welcome extends React.Component {
    render() {
        return <article id="welcome">
            <h1>Bienvenue sur l'application Imhotep</h1>
            <p>L'application permet de visualiser les données de demandes de valeur foncières de l'état. <br/> 
                Plus d'informations sur <a href="https://www.data.gouv.fr/fr/datasets/demandes-de-valeurs-foncieres/">www.data.gouv.fr</a>
            </p>
        </article>
    }
}
