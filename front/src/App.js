import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import AllPodcasts from "./pages/AllPodcasts";
import PodcastDetails from "./pages/PodcastDetails";
import EpisodeDetails from "./pages/EpisodeDetails";
import PodcastForm from "./pages/PodcastForm";

function App() {
  return (
    <Router>
      <div className="App">
        <Routes>
          <Route index element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="/podcasts" element={<AllPodcasts />} />
          <Route path="/podcasts/:id" element={<PodcastDetails />} />
          <Route
            path="/podcasts/:podcastId/episodes/:episodeId"
            element={<EpisodeDetails />}
          />
          <Route path="/podcasts/:id/update" element={<PodcastForm />} />
          <Route path="/create-podcast" element={<PodcastForm />} />
          <Route path="*" element={<Login />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
