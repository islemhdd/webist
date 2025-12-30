import React from "react";
import { createBrowserRouter, redirect } from "react-router-dom";
import Nav from "./components/Nav.jsx";
import Home from "./components/Home.jsx";
import Footer from "./components/Footer.jsx";
import About from "./components/About.jsx";
import Services from "./components/Services.jsx";
import Login from "./components/Login.jsx";
import Stats from "./components/Stats.jsx";
import ReportsCreated from "./components/ReportsCreated.jsx";
import ReportsReceived from "./components/ReportsReceived.jsx";
import ReportsShow from "./components/ReportsShow.jsx";
import ReportsCreate from "./components/ReportsCreate.jsx";
import Sanctions from "./components/Sanctions.jsx";
import Students from "./components/Students.jsx";
import Weekends from "./components/Weekends.jsx";
import Officers from "./components/Officers.jsx";
import OfficerCreate from "./components/OfficerCreate.jsx";
import OfficerEdit from "./components/OfficerEdit.jsx";
import OfficerShow from "./components/OfficerShow.jsx";
function HomeLayout() {
  return (
    <>
      <Nav />
      <Home />
      <Footer />
    </>
  );
}
function AboutLayout() {
  return (
    <>
      <Nav />
      <About />
      <Footer />
    </>
  );
}
  function LoginLayout() {
    return (
      <>
        <Nav />
        <Login />
        <Footer />
      </>
    );  

}   

function ServicesLayout() {
  return (
    <>
      <Nav />
      <Services />
      <Footer />
    </>
  );
}     

const requireAuth = () => {
  const token = localStorage.getItem("auth_token");
  const userId = localStorage.getItem("auth_user_id");
  if (!token && !userId) {
    return redirect("/");
  }
  return null;
};

const router = createBrowserRouter([
  {
    path: "/",
    element: <HomeLayout />,
  },
  {
    path: "/about",
    element: <AboutLayout />,
  },
  {
    path: "/services",  
    element: <ServicesLayout />,
  },
  {
    path: "/login",  
    element: <LoginLayout />,
  },
  {
    path: "/stats",
    element: <Stats />,
    loader: requireAuth,
  },
  {
    path: "/reports-created",
    element: <ReportsCreated />,
    loader: requireAuth,
  },
  {
    path: "/reports-create",
    element: <ReportsCreate />,
    loader: requireAuth,
  },
  {
    path: "/reports-received",
    element: <ReportsReceived />,
    loader: requireAuth,
  },
  {
    path: "/reports/:reportId",
    element: <ReportsShow />,
    loader: requireAuth,
  },
  {
    path: "/sanctions",
    element: <Sanctions />,
    loader: requireAuth,
  },
  {
    path: "/students",
    element: <Students />,
    loader: requireAuth,
  },
  {
    path: "/weekends",
    element: <Weekends />,
    loader: requireAuth,
  },
  {
    path: "/officers",
    element: <Officers />,
    loader: requireAuth,
  },
  {
    path: "/officers/create",
    element: <OfficerCreate />,
    loader: requireAuth,
  },
  {
    path: "/officers/:officerId",
    element: <OfficerShow />,
    loader: requireAuth,
  },
  {
    path: "/officers/:officerId/edit",
    element: <OfficerEdit />,
    loader: requireAuth,
  },
]);

export default router;
