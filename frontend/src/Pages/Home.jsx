import { Index as Posts } from "../Pages/Posts/Index";

export default function Home() {
    return (
        <div>
            <h1 className="my-5 text-xl font-semibold text-center">
                Welcome to my Blog
            </h1>
            <p>Check out my latest posts below:</p>
            <Posts/>
        </div>
    );
}
