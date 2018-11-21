import React from 'react';
import { Container } from 'reactstrap';
import Header from './Header'

export default props => (
  <div>
    <Header logout={props.logout.bind()} login={props.login.bind()} loggedIn={props.loggedIn} username={props.username} role={props.role} />
    <Container>
      {props.children}
    </Container>
  </div>
);
