import React from 'react';
import { connect } from 'react-redux';
import {bindActionCreators} from "redux";
import * as user from "../actions/user";
import history from '../history'

class Login extends React.Component {
    constructor(props){
        super(props);
        this.state = {
            username: this.props.username !== '' ? this.props.username : '',
            password: this.props.password !== '' ? this.props.password : '',
        }
    }

    changeUsername = (event) => {
        this.setState({
            username: event.target.value
        })
    };

    changePassword = (event) => {
        this.setState({
            password: event.target.value
        })
    };

    submitForm = () => {
        this.props.actions.loginUser(this.state.username, this.state.password, this.state.role);
    };

    componentWillReceiveProps(){
        if(!this.props.loggedIn){
            history.push('/');
        }
    }

    render(){
        return (
            <div className="mdl-grid">
                    <div className="wrapy mdl-cell mdl-cell--6-col mdl-shadow--6dp">
                        <div className="mdl-card__title">
                            <h2 className="mdl-card__title-text">Login</h2>
                        </div>
                        <div className="mdl-card__supporting-text">
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input className="mdl-textfield__input" value={this.state.username} onChange={this.changeUsername} type="text" id="username" />
                                <label className="mdl-textfield__label" htmlFor="username">Username</label>
                            </div>
                            <div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input className="mdl-textfield__input" value={this.state.password} onChange={this.changePassword} type="password" id="password" />
                                <label className="mdl-textfield__label" htmlFor="password">Password</label>
                            </div>
                            {/*<div className="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                <input className="mdl-textfield__input" value={this.state.role} onChange={this.changeRole} type="text" id="role" />
                                <label className="mdl-textfield__label" htmlFor="role">role</label>
        </div>--> */}
                        </div>
                        <div className="mdl-card__actions mdl-card--border">
                            <button onClick={this.submitForm}
                                className="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
                                Submit
                            </button>
                        </div>
                </div>
            </div>
        );

    }
}
export default connect(store => ({
        loggedIn: store.user.loggedIn,
        username: store.user.username,
        password: store.user.password,
    }),
    (dispatch) => ({
        actions: bindActionCreators({...user}, dispatch)
    })
)(Login);