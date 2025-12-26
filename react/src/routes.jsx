import React from "react";
import { createBrowserRouter } from "react-router-dom";
import Nav from "./components/Nav.jsx";
import Home from "./components/Home.jsx";
import Footer from "./components/Footer.jsx";

function HomeLayout() {
  return (
    <>
      <Nav />
      <Home />
      <Footer />
    </>
  );
}

const router = createBrowserRouter([
  {
    path: "/",
    element: <HomeLayout />,
  },
 
]);

export default router;
