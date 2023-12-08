import React, { useState, useEffect } from "react";
import './BookmarkApp.css';
const apiUrl = "http://localhost:3000/backend/api";

const BookmarkApp = () => {
  const [bookmarks, setBookmarks] = useState([]);
  const [newBookmark, setNewBookmark] = useState({ title: "", URL: "" });
  const [searchTerm, setSearchTerm] = useState("");
  const [updateData, setUpdateData] = useState({ id: "", title: "", URL: "" });

  useEffect(() => {
    readAllBookmarks();
  }, []);

  const addNewBookmark = async () => {
    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(newBookmark),
    };

    await fetch(apiUrl + "/create.php", options);
    setNewBookmark({ title: "", URL: "" });
    readAllBookmarks();
  };

  const deleteBookmark = async (id) => {
    const options = {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
    };

    await fetch(apiUrl + `/delete.php?id=${id}`, options);
    readAllBookmarks();
  };

  const readAllBookmarks = async () => {
    const response = await fetch(apiUrl + "/readAll.php");
    const bookmarks = await response.json();
    setBookmarks(bookmarks);
  };

  const handleUpdate = (bookmark) => {
    setUpdateData({
      id: bookmark.id,
      title: bookmark.title,
      URL: bookmark.URL,
    });
  };

  const handleUpdateSubmit = async (e) => {
    e.preventDefault();

    const requestOptions = {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(updateData),
    };

    await fetch(apiUrl + `/update.php?id=${updateData.id}`, requestOptions);
    handleSearch();
    setUpdateData({ id: "", title: "", URL: "" });
  };

  const handleSearch = async () => {
    const response = await fetch(
      apiUrl + `/searchByTitle.php?searchTerm=${searchTerm}`
    );
    const data = await response.json();
    if (Array.isArray(data)) {
      setBookmarks(data);
    } else {
      console.error("Received non-array response:", data);
      setBookmarks([]);
    }
  };

  return (
    <div className="container">
      {/* Search bar */}
      <input
        type="text"
        placeholder="Search by title"
        value={searchTerm}
        onChange={(e) => setSearchTerm(e.target.value)}
      />

      {/* Add Bookmark form */}
      <div>
        <input
          type="text"
          placeholder="Enter bookmark URL"
          value={newBookmark.URL}
          onChange={(e) =>
            setNewBookmark({ ...newBookmark, URL: e.target.value })
          }
        />
        <input
          type="text"
          placeholder="Enter bookmark title"
          value={newBookmark.title}
          onChange={(e) =>
            setNewBookmark({ ...newBookmark, title: e.target.value })
          }
        />
        <button className="add-btn" onClick={addNewBookmark}>Add Bookmark</button>
      </div>

      {bookmarks.length === 0 ? (
        <p>No Bookmarks available.</p>
      ) : (
        bookmarks.map((bookmark) => (
          <div key={bookmark.id} className="bookmark-item">
            <div className="bookmark-header">
              <p className="bookmarks-title">{bookmark.title}</p>
              <p className="date-added">{bookmark.date_added}</p>
            </div>
            <div className="bookmark-URL-box">
              <p className="bookmark-URL">{bookmark.URL}</p>
            </div>
            <button
              onClick={() => deleteBookmark(bookmark.id)}
              className="delete-btn"
            >
              Delete
            </button>
            <button
              onClick={() => handleUpdate(bookmark)}
              className="update-btn"
            >
              Update
            </button>

            {/* Update Form */}
            {updateData.id === bookmark.id && (
              <form onSubmit={handleUpdateSubmit}>
                <label>
                  New Title:
                  <input
                    type="text"
                    value={updateData.title}
                    onChange={(e) =>
                      setUpdateData({
                        ...updateData,
                        title: e.target.value,
                      })
                    }
                  />
                </label>
                <label>
                  New URL:
                  <input
                    type="text"
                    value={updateData.URL}
                    onChange={(e) =>
                      setUpdateData({
                        ...updateData,
                        URL: e.target.value,
                      })
                    }
                  />
                </label>
                <button type="submit">Submit Update</button>
              </form>
            )}
          </div>
        ))
      )}
    </div>
  );
};

export default BookmarkApp;
