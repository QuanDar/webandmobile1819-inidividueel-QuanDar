import React from 'react';
import App from "./components/App";
import Home from "./components/Home";
import Login from "./components/LoginPage"

export default (
    <Route path="/" component={App}>
        <IndexRoute component={Home} />
        <Route path="login" component={Login} />
    </Route>
)