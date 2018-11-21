import React from 'react'

export default props => (
    <div className="mdl-layout mdl-layout--fixed-header">
        <header className="mdl-layout__header">
            <div className="mdl-layout__header-row">
                <span className="mdl-layout-title">Bulletinboard</span>
                <div className="mdl-layout-spacer" />
                <nav className="mdl-navigation mdl-layout--large-screen-only">
                    <i className="mdl-navigation__link" onClick={props.loggedIn ? props.logout : props.login}>{props.loggedIn ? 'Role: '+ props.role + ' | Not ' + props.username + '? Click here' : 'Login'}</i>
                </nav>
            </div>
        </header>
        <div className="mdl-layout__drawer">
            <span className="mdl-layout-title">Guestbook</span>
            <nav className="mdl-navigation">
                <i className="mdl-navigation__link" onClick={props.loggedIn ? props.logout : props.login}>{props.loggedIn ? 'Not ' + props.username + '? Click here' : 'Login'}</i>
            </nav>
        </div>
    </div>
);
