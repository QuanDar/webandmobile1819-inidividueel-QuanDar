import * as types from '../actions/actionTypes';

const initialState = {
    messages: [],
    message: null,
    messageSearchResults: [],
    success: null,
};

const comments = (state = initialState, action) => {
    switch (action.type) {
        case types.POST_COMMENT_BY_MESSAGE_ID:
            console.log("Test");
            const addedComment = state.messages.map(message => {
                if(message.id === action.id){
                    message.comments = [...message.comments, action.payload];
                    return message
                }
                return message
            });
            return {
                ...state,
                messages: addedComment
            };
        default:
            return state
    }
};

export default comments