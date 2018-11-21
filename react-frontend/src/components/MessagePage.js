import React from 'react'
import connect from "react-redux/es/connect/connect";
import {bindActionCreators} from "redux";
import * as messages from "../actions/messages";
import CardWrapper from "../wrappers/CardWrapper";
import Message from "./Message";

class MessagePage extends React.Component {
    constructor(props){
        super(props);
        this.props.actions.fetchMessageById(this.props.match.params.id)
    }

    changeComment = (event) => {
        this.setState({
            comment: event.target.value
        })
    };

    addComment = (event) => {
        let comment = this.state.comment;
        let id = event.target.value;
        this.props.actions.postComment(id, comment);
    };

    upvoteClick = (id) => {
        this.props.actions.upvoteMessage(id)
    };

    downvoteClick = (id) => {
        this.props.actions.downvoteMessage(id)
    };

    deleteMessageClick = (id) => {
        this.props.actions.deleteMessage(id)
    };

    render() {
        const {message} = this.props;
        return (
            <div>
                {message.map((message, index) =>
                    <CardWrapper key={index}>
                        <Message id={message.id} role={this.props.role} content={message.content} upvoteClick={this.upvoteClick} downvoteClick={this.downvoteClick} upvotes={message.upVotes} downvotes={message.downVotes} date={message.date} category={message.category}/>
                    </CardWrapper>
                )}
            </div>
        );
    }
}

export default connect(store => ({
        message: store.messages.message,
        role: store.user.role
    }),
    (dispatch) => ({
        actions: bindActionCreators({...messages}, dispatch)
    })
)(MessagePage);
