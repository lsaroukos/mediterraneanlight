export default function StarRating({ rating = 0 }) {
  const MAX_STARS = 5;

  const getStarType = (index) => {
    const diff = rating - index;

    if (diff >= 1) return "full";
    if (diff >= 0.5) return "half";
    return "empty";
  };

  return (
    <div style={{ display: "flex", gap: "4px" }}>
      {[...Array(MAX_STARS)].map((_, i) => {
        const type = getStarType(i);

        return (
          <span key={i} style={{ fontSize: "1.5rem" }}>
            {type === "full" && "★"}
            {type === "half" && "⯨"} {/* Unicode half-star approximation */}
            {type === "empty" && "☆"}
          </span>
        );
      })}
    </div>
  );
}
