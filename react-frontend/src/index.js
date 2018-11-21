import React from 'react';
import ReactDOM from 'react-dom';
import { Router, Route } from 'react-router-dom'
import { Provider } from "react-redux"
import configureStore from "./store/configureStore"
import { PersistGate } from 'redux-persist/integration/react'

import App from './components/App';
import LoginPage from './components/LoginPage';
import * as serviceWorker from './serviceWorker';
import history from './history';
import MessagePage from "./components/MessagePage";

const {store, persistor} = configureStore();
ReactDOM.render((
    <Provider store={store}>
        <PersistGate loading={null} persistor={persistor}>
            <Router history={history}>
                <div>
                    <Route exact path="/" component={App} />
                    <Route path="/login" component={LoginPage} />
                    <Route path="/message/:id" component={MessagePage} />
                </div>
            </Router>
        </PersistGate>
    </Provider>
), document.getElementById('root'));

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: http://bit.ly/CRA-PWA
serviceWorker.unregister();
