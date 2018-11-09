import React from 'react'
import PropTypes from "prop-types";
import '../assets/css/message.css'

const Message = (props) => {
    return (

        <div className="demo-card-wide mdl-shadow--3dp">
            <div className="mdl-card__title">
                <h3 className="mdl-card__title-text"><a href={'/message/' + props.id}>Message nr. {props.id}</a></h3>
            </div>
            <div className="mdl-card__supporting-text">
                {props.content}
            </div>
            {props.isAdmin ? (
                <button onClick={() => props.deleteMessageClick(props.id)} className="button mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored">
                    DELETE
                </button>
            ) : (
                <div></div>
            )}
             <div className="mdl-card__supporting-text">
                Role: {props.role}
            </div>
            <div className="mdl-card__supporting-text">
            <span onClick={() => props.upvoteClick(props.id)} className="mdl-chip mdl-chip--contact upvote">
                <span className="mdl-chip__contact mdl-color--blue mdl-color-text--white">{props.upvotes}</span>
                <span className="mdl-chip__text">Upvote</span>
            </span>
            <span onClick={() => props.downvoteClick(props.id)} className="mdl-chip mdl-chip--contact">
                <span className="mdl-chip__contact mdl-color--red mdl-color-text--white">{props.downvotes}</span>
                <span className="mdl-chip__text">Downvote</span>
            </span>
            </div>
            <div className="mdl-card__actions mdl-card--border">
               <div className="">
                   Posted on {props.date}
                   <br/>
               </div>
               <div className="mdl-card__subtitle-text">
                    Category: {props.category}
               </div>
            </div>
        </div>
    )
};

Message.propTypes = {
    id: PropTypes.number,
    isAdmin: PropTypes.isAdmin,
    role: PropTypes.role,
    content: PropTypes.string,
    category: PropTypes.string,
    date: PropTypes.string,
    upvotes: PropTypes.number,
    downvotes: PropTypes.number,
};

export default Message;