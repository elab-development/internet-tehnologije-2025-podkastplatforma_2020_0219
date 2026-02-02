import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import AllPodcasts from "./pages/AllPodcasts";


function App() {
  return (
    <Router>
      <div className="App">
        <Routes>
          <Route index element={<Login />} />
          <Route path="/register" element={<Register />} />
            <Route path="/podcasts" element={<AllPodcasts />} />
          <Route path='*' element={<Login />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
