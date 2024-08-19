import { useState, useEffect } from "react";
import axios from "axios";
import {Link, useLocation, useParams } from "react-router-dom";

export default function Show() {
    const location = useLocation();
    const { postId } = useParams();
    console.log(postId); //
    
    const [post, setPost] = useState([]);

    useEffect(() => {
        const fetchPosts = async () => {
            try {
                const response = await axios.get(
                    `http://blog.test/api/posts/${postId}`
                );
                setPost(response.data); 
            } catch (error) {
                console.error("Error fetching data: ", error);
            }
        };

        fetchPosts();
    }, []);

    //

    return (
        <div>
            <div className="flex flex-col space-y-10">
                <h1 className="mx-auto text-xl">{post.title}</h1>

                <h2>
                    By
                    <span className="font-bold"> {post.author}</span>
                </h2>

                <div className="leading-7 text-left">{post.body}</div>
            </div>

            <Link
                to="/"
                className="inline-block px-4 py-2 mt-10 text-black border rounded-md hover:bg-gray-200"
            >
                Go back
            </Link>
        </div>
    );
}
