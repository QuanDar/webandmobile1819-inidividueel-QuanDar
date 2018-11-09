import React from 'react'
import Layout from './Layout';
import Home from './Home';
import connect from "react-redux/es/connect/connect";
import {bindActionCreators} from "redux";
import * as user from "../actions/user";
import history from "../history";

class App extends React.Component {

    logout = () => {
        if(this.props.loggedIn){
            this.props.actions.logoutUser();
        }
        history.push('/login');
    };

    login = () => {
        history.push('/login');
    };

    render() {
        return (
            <Layout logout={this.logout} login={this.login} loggedIn={this.props.loggedIn} username={this.props.username} role={this.props.role}>
                <Home/>
            </Layout>
        )
    }
}

export default connect(store => ({
        loggedIn: store.user.loggedIn,
        username: store.user.username,
        password: store.user.password,
        role: store.user.role
    }),
    (dispatch) => ({
        actions: bindActionCreators({...user}, dispatch)
    })
)(App);
  