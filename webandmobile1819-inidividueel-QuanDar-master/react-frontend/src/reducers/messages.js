import * as types from '../actions/actionTypes';

const initialState = {
    messages: [],
    message: [],
    messageSearchResults: [],
    categories: [],
    selectedCategory: '',
    search: ''
};

const messages = (state = initialState, action) => {
    switch (action.type) {
        case types.FETCH_MESSAGES:
            return {
                ...state,
                messages: action.payload,
                messageSearchResults: action.payload
            };
        case types.FETCH_CATEGORIES:
            return {
                ...state,
                categories: action.payload
            };
        case types.FETCH_MESSAGE_BY_ID:
            return {
                ...state,
                message: action.payload
            };
        case types.SELECT_CATEGORY:
            return {
                ...state,
                selectedCategory: action.category
            };
        case types.FETCH_MESSAGES_BY_CONTENT:
            return {
                ...state,
                messageSearchResults: action.payload
            };
        case types.FETCH_MESSAGES_BY_CONTENT_AND_CATEGORY:
            return {
                ...state,
                messageSearchResults: action.payload
            };
        case types.FILTER_MESSAGES_BY_CONTENT:
            return {
                ...state,
                search: action.search,
                messageSearchResults: state.messages.filter(message => {
                    if(action.category !== ''){
                        return message.content.includes(action.search) && message.category === action.category
                    }
                    else{
                        return message.content.includes(action.search)
                    }
                })
            };
        case types.UPVOTE_MESSAGE:
            const upvotedMessages = state.messages.map(message => {
                if(message.id === action.id){
                    message.upVotes = message.upVotes+1;
                    return message
                }
                return message
            });

            return {
                ...state,
                messages: upvotedMessages
            };
        case types.DOWNVOTE_MESSAGE:
            const downvotedMessages = state.messages.map(message => {
                if(message.id === action.id){
                    message.downVotes = message.downVotes+1;
                    return message
                }
                return message
            });

            return {
                ...state,
                messages: downvotedMessages
            };
        case types.POST_COMMENT_BY_MESSAGE_ID:
            const addedComment = state.messages.map(message => {
                if(message.id === action.id){
                    message.comments = [...message.comments, action.payload]
                    return message
                }
                return message
            });
            alert('Uw token is: ' + action.payload[0].token)
            return {
                ...state,
                //messages: addedComment
            };
        default:
            return state
    }
};

export default messages