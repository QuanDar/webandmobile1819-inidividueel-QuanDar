import React from 'react';
import { connect } from 'react-redux';
import PropTypes from "prop-types";
import '../assets/css/search.css'

const Search = (props) => {
    return (
        <form action="#">
            <div className="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
                <label className="mdl-button mdl-js-button mdl-button--icon" htmlFor="sample6">
                    <i className="material-icons">search</i>
                </label>
                <div className="mdl-textfield__expandable-holder">
                    <input className="mdl-textfield__input" type="text" id="sample6" onChange={props.searchChange} />
                        <label className="mdl-textfield__label" htmlFor="sample-expandable">Expandable Input</label>
                </div>
            </div>
            <select onChange={props.selectCategory}>
                <option value="">All</option>
                {props.categories}
            </select>

        </form>
    )
};

Comment.propTypes = {
    id: PropTypes.number,
    content: PropTypes.string,
    username: PropTypes.string,
    date: PropTypes.string
};

export default connect()(Search);