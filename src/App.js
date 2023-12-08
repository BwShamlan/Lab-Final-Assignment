import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import './App.css';
import React from "react";
import Home from "./components/Home.js";
import About from "./components/About.js";

function App() {
  return (
    <BrowserRouter>
      <div className='container'>
        <header>
          <nav className="navbar">
            <ul>
              <li><Link to="/">Home</Link></li>
              <li><Link to="/about">About</Link></li>
            </ul>
          </nav>
        </header>
        <main>
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/about" element={<About />} />
          </Routes>
        </main>
        <footer>
          <p>Made for CPIT405 by Bader Shamlan</p>
        </footer>
      </div>
    </BrowserRouter>
  );
}

export default App;