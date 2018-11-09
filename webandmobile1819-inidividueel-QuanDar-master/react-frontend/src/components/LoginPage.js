import React from 'react'
import Layout from './Layout';
import Login from './Login';
import connect from "react-redux/es/connect/connect";
import {bindActionCreators} from "redux";
import * as user from "../actions/user";
import history from "../history";

class LoginPage extends React.Component {

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
                <Login/>
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
)(LoginPage);
