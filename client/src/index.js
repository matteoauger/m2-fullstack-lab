import React from 'react';
import ReactDOM from 'react-dom';
import { createStore, combineReducers, applyMiddleware } from 'redux';
import { Provider } from 'react-redux';
import thunk from 'redux-thunk';
import { reducer as form } from 'redux-form';
import { Route, Switch } from 'react-router-dom';
import createBrowserHistory from 'history/createBrowserHistory';
import {
    ConnectedRouter,
    connectRouter,
    routerMiddleware
} from 'connected-react-router';
import 'bootstrap/dist/css/bootstrap.css';
import 'font-awesome/css/font-awesome.css';
import * as serviceWorker from './serviceWorker';
import Welcome from './components/welcome/Welcome';
import TimeSeriesGraph from "./components/timeseries/TimeSeriesGraph";
import BarChart from './components/barchart/BarChart';
import PieChart from './components/piechart/PieChart';
import Header from './components/header/Header';

const history = createBrowserHistory();
const store = createStore(
    combineReducers({
        router: connectRouter(history),
        form,
    }),
    applyMiddleware(routerMiddleware(history), thunk)
);

ReactDOM.render(
    <Provider store={store}>
        <ConnectedRouter history={history}>
            <section>
                <Header />
                <Switch>
                    <Route path="/" component={Welcome} strict={true} exact={true} />
                    <Route path="/timeseries" component={TimeSeriesGraph} strict={true} exact={true} />
                    <Route path="/barchart" component={BarChart} strict={true} exact={true} />
                    <Route path="/piechart" component={PieChart} strict={true} exact={true} />
                    <Route render={() => <h1>Not Found</h1>} />
                </Switch>
            </section>

        </ConnectedRouter>
    </Provider>,
    document.getElementById('root')
);

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: http://bit.ly/CRA-PWA
serviceWorker.unregister();
