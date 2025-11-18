// Authorization token that must have been created previously. See : https://developer.spotify.com/documentation/web-api/concepts/authorization
const token = 'BQCAZoRNooWpor7w-h9UK523Inoqg46a_AKUNZwemQ-UEDpeZ8pr7Cw7XO4P-rSGygfaDSSXWih-R330NcZnXBAkg875HyNBMwmjocea-1PFJf-chxWLqfKXY1M4bH6yfNx7T6_C0zW3Iq8exham2FGVxAG4Hrk49RvlSAh_xlk1QkT82ZhSHEaZ28l03Dwe1OGg1U65km3cqmM13RkR0-qYfkyWi5f9tE_KsjwqFu1jMpYV7pbwoaVNrPYyBGC3OgyrxsrYK3R4cQwAAZUg-vEGDttban4xmGxrSon8G0sotCmZJQoecgcNgNuo1HH8';
async function fetchWebApi(endpoint, method, body) {
    const res = await fetch(`https://api.spotify.com/${endpoint}`, {
        headers: {
            Authorization: `Bearer ${token}`,
        },
        method,
        body: JSON.stringify(body)
    });
    return await res.json();
}

async function getTopTracks() {
    // Endpoint reference : https://developer.spotify.com/documentation/web-api/reference/get-users-top-artists-and-tracks
    return (await fetchWebApi(
        'v1/me/top/tracks?time_range=long_term&limit=5', 'GET'
    )).items;
}

const topTracks = await getTopTracks();
console.log(
    topTracks?.map(
        ({ name, artists }) =>
            `${name} by ${artists.map(artist => artist.name).join(', ')}`
    )
);