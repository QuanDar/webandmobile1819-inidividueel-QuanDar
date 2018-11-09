import React from 'react';
import '../assets/css/card.css'

class CardWrapper extends React.Component{
    render(){
        return (
            <div className="mdl-grid">
                <div className="wrapy mdl-cell mdl-cell--6-col mdl-shadow--6dp">
                    {this.props.children}
                </div>
            </div>
        )
    }
}

export default CardWrapper
