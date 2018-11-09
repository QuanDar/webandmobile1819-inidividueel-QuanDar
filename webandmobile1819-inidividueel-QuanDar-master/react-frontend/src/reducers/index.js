import { combineReducers } from 'redux'
import messages from './messages'
import comments from './comments'
import user from './user'

export default combineReducers({
  messages, user
})
