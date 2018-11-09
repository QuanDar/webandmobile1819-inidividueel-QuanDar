import React from 'react';
import { connect } from 'react-redux';
import Message from "./Message";
import Comment from "./Comment";
import WriteComment from "./WriteComment";
import {bindActionCreators} from "redux";
import * as messages from "../actions/messages";
import CardWrapper from '../wrappers/CardWrapper'
import Search from "./Search";
import Category from "./Category";
import * as types from './../actions/actionTypes';

class Home extends React.Component {
    constructor(props){
        super(props);
        this.props.actions.fetchMessages();
        this.props.actions.fetchCategories();
        this.state = {
            comment: '',
        }
    }

    searchChange(event){
        this.props.actions.filterMessagesByContent(event.target.value, this.props.selectedCategory);
    }

    selectCategory(event){
        this.props.actions.selectCategory(event.target.value);
        this.props.actions.filterMessagesByContent(this.props.search, event.target.value);
    }

    changeComment = (event) => {
        this.setState({
            comment: event.target.value
        })
    }

    addComment = (event) => {
        let comment = this.state.comment;
        let id = event.target.value;
        this.props.actions.postComment(id, comment);
    }

    upvoteClick = (id) => {
        this.props.actions.upvoteMessage(id)
    };

    downvoteClick = (id) => {
        this.props.actions.downvoteMessage(id)
    };

    deleteMessageClick = (id) => {
        this.props.actions.deleteMessage(id)
    };

    render(){
        const {messages} = this.props;

        var categories = this.props.categories.map((category, index) => <Category key={index} title={category.category}/>);
        const isAdmin = false;
        if (this.props.role == types.ROLE_ADMIN){
            this.isAdmin = true;
        }

        return (
            <div>
                <CardWrapper>
                    <Search searchChange={this.searchChange.bind(this)} selectCategory={this.selectCategory.bind(this)} categories={categories} />
                </CardWrapper>
                {messages.map((message, index) =>
                    <CardWrapper key={index}>
                        <Message id={message.id} isAdmin={this.isAdmin} role={this.props.role} content={message.content} deleteMessageClick={this.deleteMessageClick} upvoteClick={this.upvoteClick} downvoteClick={this.downvoteClick} upvotes={message.upVotes} downvotes={message.downVotes} date={message.date} category={message.category}/>

                        {message.comments.map((comment, index) =>
                        <Comment key={index} username={comment.token} content={comment.content} />
                        )}

                        <WriteComment id={message.id} addComment={this.addComment.bind()} changeComment={this.changeComment} />
                    </CardWrapper>
                )}
            </div>
        );

    }
}
export default connect(store => ({
        messagesAll: store.messages.messages,
        messages: store.messages.messageSearchResults,
        message: store.messages.message,
        categories: store.messages.categories,
        selectedCategory: store.messages.selectedCategory,
        search: store.messages.search,
        role: store.user.role
    }),
    (dispatch) => ({
        actions: bindActionCreators({...messages}, dispatch)
    })
)(Home);