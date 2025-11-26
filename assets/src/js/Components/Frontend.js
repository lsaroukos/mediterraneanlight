// components/Frontend.js
import { useIntersectionAnimation } from "../hooks/useIntersectionAnimation";

export default function Frontend() {
  // The selector matches elements already in the DOM
  useIntersectionAnimation(".decorated-title");

  return null; // Nothing React renders itself
}
