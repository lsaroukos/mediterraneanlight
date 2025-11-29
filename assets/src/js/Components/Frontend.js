// components/Frontend.js
import { useIntersectionAnimation } from "../hooks/useIntersectionAnimation";
import Shop from "./Shop";

export default function Frontend() {
  // The selector matches elements already in the DOM
  useIntersectionAnimation(".decorated-title");

  return (
    <Shop />
  ); // Nothing React renders itself
}
